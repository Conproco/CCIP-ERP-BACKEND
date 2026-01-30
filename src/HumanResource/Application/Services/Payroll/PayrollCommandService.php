<?php

declare(strict_types=1);

namespace Src\HumanResource\Application\Services\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\EmployeeRepositoryInterface;
use Src\HumanResource\Application\Data\Payroll\PayrollData;
use Src\HumanResource\Application\Data\Payroll\StorePayrollData;
use Illuminate\Support\Facades\DB;
use App\Models\PayrollDeductionInstallment;
use Carbon\Carbon;

class PayrollCommandService
{
    public function __construct(
        private PayrollRepositoryInterface $payrollRepository,
        private EmployeeRepositoryInterface $employeeRepository,
        private PayrollCalculationService $calculationService
    ) {
    }

    /**
     * Update payroll state to true (closed/completed)
     */
    public function updateState(int $payrollId): PayrollData
    {
        $payroll = $this->payrollRepository->updateState($payrollId, true);
        return PayrollData::fromModel($payroll);
    }

    /**
     * Create a new payroll with all associated records
     */
    public function store(StorePayrollData $data): PayrollData
    {
        return DB::transaction(function () use ($data) {
            // 1. Create Payroll
            $payroll = $this->payrollRepository->create([
                'month' => $data->month,
                'state' => $data->state,
            ]);

            // 2. Create associated pensions
            $pensions = collect();
            foreach ($data->pension_system as $pensionData) {
                $pension = $this->payrollRepository->createPension($payroll->id, [
                    'type' => $pensionData->type,
                    'commission_flow' => $pensionData->commission_flow,
                    'annual_commission_balance' => $pensionData->annual_commission_balance,
                    'insurance_premium' => $pensionData->insurance_premium,
                    'mandatory_contribution' => $pensionData->mandatory_contribution,
                ]);
                $pensions->push($pension);
            }

            // 3. Get active employees for the month
            $employees = $this->employeeRepository->getActiveEmployeesForMonth($data->month);

            // 4. Process each employee
            foreach ($employees as $employee) {
                $contract = $employee->contract;

                // Calculate days worked
                $days = $this->calculationService->calculateDays(
                    $contract->hire_date,
                    $contract->fired_date,
                    $data->month
                );

                // Find employee's pension (flexible match: handles 'Integra' matching 'AFP Integra')
                $employeePension = $pensions->first(function ($pension) use ($contract) {
                    $contractType = strtolower(trim($contract->pension_type));
                    $pensionType = strtolower(trim($pension->type));

                    // Exact match or contains match
                    return $contractType === $pensionType
                        || str_contains($pensionType, $contractType)
                        || str_contains($contractType, $pensionType);
                });

                // Throw error if pension type not found
                if (!$employeePension) {
                    throw new \Exception(
                        "No se encontró el tipo de pensión '{$contract->pension_type}' para el empleado ID {$employee->id}. " .
                        "Tipos disponibles: " . $pensions->pluck('type')->implode(', ')
                    );
                }

                // Create PayrollDetail
                $payrollDetail = $this->payrollRepository->createPayrollDetail([
                    'payroll_id' => $payroll->id,
                    'employee_id' => $employee->id,
                    'basic_salary' => $contract->basic_salary,
                    'amount_travel_expenses' => $contract->amount_travel_expenses,
                    'life_ley' => $contract->life_ley,
                    'discount_remuneration' => $contract->discount_remuneration,
                    'discount_sctr' => $contract->discount_sctr,
                    'hire_date' => $contract->hire_date,
                    'fired_date' => $contract->fired_date,
                    'days' => $days,
                    'days_taken' => $contract->days_taken,
                    'pension_id' => $employeePension->id,
                ]);


                // Calculate proportional amount
                $amount = $this->calculationService->calculateAmount($days, (float) $contract->basic_salary);

                // 5. Create automatic incomes
                $this->createAutomaticIncomes($payrollDetail->id, $amount, $contract->amount_travel_expenses);

                // 6. Create automatic contributions
                $this->createAutomaticContributions($payrollDetail, $employeePension, $amount);

                // 7. Calculate deductions
                $this->calculateDeductions($data->month, $payrollDetail->id, $employee->id);
            }

            // Reload payroll with relationships
            $payroll = $this->payrollRepository->find($payroll->id);
            $payroll->total_amount = 0; // Will be calculated by SQL aggregation

            return PayrollData::fromModel($payroll);
        });
    }

    /**
     * Create automatic income entries for an employee
     */
    private function createAutomaticIncomes(int $payrollDetailId, float $salaryAmount, ?float $travelExpenses): void
    {
        // Income param 6 = Basic salary
        $this->payrollRepository->createPayrollDetailIncome($payrollDetailId, 6, $salaryAmount);

        // Income param 12 = Travel expenses (if applicable)
        if ($travelExpenses && $travelExpenses > 0) {
            $this->payrollRepository->createPayrollDetailIncome($payrollDetailId, 12, $travelExpenses);
        }
    }

    /**
     * Create automatic tax/contribution entries based on pension type
     */
    private function createAutomaticContributions(object $payrollDetail, ?object $pension, float $amount): void
    {
        if (!$pension) {
            return;
        }

        // ONP pension
        if ($pension->type === 'ONP') {
            // TAC param 6 = ONP contribution
            $this->payrollRepository->createPayrollDetailContribution(
                $payrollDetail->id,
                6,
                $amount * ($pension->mandatory_contribution / 100)
            );
        } else {
            // AFP pensions
            // TAC param 5 = Mandatory AFP contribution
            $this->payrollRepository->createPayrollDetailContribution(
                $payrollDetail->id,
                5,
                $amount * ($pension->mandatory_contribution / 100)
            );

            // TAC param 4 = Insurance premium
            $this->payrollRepository->createPayrollDetailContribution(
                $payrollDetail->id,
                4,
                $amount * ($pension->insurance_premium / 100)
            );

            // TAC param 1 = Commission flow (if discount_remuneration is set)
            if (!empty($payrollDetail->discount_remuneration)) {
                $this->payrollRepository->createPayrollDetailContribution(
                    $payrollDetail->id,
                    1,
                    $amount * ($pension->commission_flow / 100)
                );
            }
        }

        // TAC param 9 = EsSalud (9% for all employees)
        $this->payrollRepository->createPayrollDetailContribution($payrollDetail->id, 9, $amount * 0.09);
    }

    /**
     * Calculate and create deductions from pending installments
     */
    private function calculateDeductions(string $month, int $payrollDetailId, int $employeeId): void
    {
        $monthCarbon = Carbon::parse($month);

        $installments = PayrollDeductionInstallment::where('employee_id', $employeeId)
            ->whereMonth('approximate_payment_date', $monthCarbon->month)
            ->whereYear('approximate_payment_date', $monthCarbon->year)
            ->where('payment_status', '!=', 'Pagado')
            ->get();

        if ($installments->isEmpty()) {
            return;
        }

        $total = $installments->sum('amount');

        // Discount param 4 = Deduction
        $discount = $this->payrollRepository->createPayrollDetailDiscount($payrollDetailId, 4, $total);

        // Mark installments as paid
        $installments->each(function ($item) use ($discount) {
            $item->update([
                'payment_status' => 'Pagado',
                'payroll_detail_monetary_discount_id' => $discount->id,
            ]);
        });
    }
}
