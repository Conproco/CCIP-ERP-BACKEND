<?php

namespace Src\HumanResource\Infrastructure\Persistence\Payroll;

use App\Models\PayrollDetail;
use App\Models\PayrollDetailTaxAndContribution;
use Illuminate\Support\Collection;
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDetailRepositoryInterface;
use Src\Shared\Domain\Enums\ApprovalState;

class EloquentPayrollDetailRepository implements PayrollDetailRepositoryInterface
{
    public function __construct(
        private PayrollDetail $model
    ) {
    }

    public function getDetailsByPayrollWithFilters(
        int $payrollId,
        ?string $search = null,
        ?array $pensionTypes = null
    ): Collection {
        $query = $this->model
            ->with([
                'payroll',
                'payroll_detail_expense',
                'employee:id,dni,name,lastname',
                'pension:id,type'
            ])
            // Aggregate income paid total
            ->withSum('payroll_detail_monetary_income', 'paid_amount')
            // Aggregate discount total
            ->withSum('payroll_detail_monetary_discounts', 'amount')
            // Aggregate expenses (excluding 'Gasto financiero')
            ->withSum([
                'payroll_detail_expense as payment_details_sum' => function ($query) {
                    $query->where('expense_type', '!=', 'Gasto financiero');
                }
            ], 'amount')
            // Aggregate employee TAC (with join to get type)
            ->addSelect([
                'payroll_details.*',
                'employee_tac_total' => PayrollDetailTaxAndContribution::selectRaw('COALESCE(SUM(amount), 0)')
                    ->join('tax_and_contribution_params', 'payroll_detail_tax_and_contributions.t_a_c_param_id', '=', 'tax_and_contribution_params.id')
                    ->whereColumn('payroll_detail_tax_and_contributions.payroll_detail_id', 'payroll_details.id')
                    ->where('tax_and_contribution_params.type', 'employee'),
                'employer_tac_total' => PayrollDetailTaxAndContribution::selectRaw('COALESCE(SUM(amount), 0)')
                    ->join('tax_and_contribution_params', 'payroll_detail_tax_and_contributions.t_a_c_param_id', '=', 'tax_and_contribution_params.id')
                    ->whereColumn('payroll_detail_tax_and_contributions.payroll_detail_id', 'payroll_details.id')
                    ->where('tax_and_contribution_params.type', 'employer'),
                'essalud' => PayrollDetailTaxAndContribution::selectRaw('COALESCE(SUM(amount), 0)')
                    ->join('tax_and_contribution_params', 'payroll_detail_tax_and_contributions.t_a_c_param_id', '=', 'tax_and_contribution_params.id')
                    ->whereColumn('payroll_detail_tax_and_contributions.payroll_detail_id', 'payroll_details.id')
                    ->where('tax_and_contribution_params.code', '0804'),
            ])
            ->where('payroll_id', $payrollId);

        // Filter by employee name/lastname
        if ($search) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%");
            });
        }

        // Filter by pension types
        if ($pensionTypes && count($pensionTypes) > 0) {
            $query->whereHas('pension', function ($q) use ($pensionTypes) {
                $q->whereIn('type', $pensionTypes);
            });
        }

        $results = $query->get();

        // Transform to add computed fields (done in PHP but using pre-loaded sums)
        return $results->map(function ($item) {
            $income_paid_total = (float) ($item->payroll_detail_monetary_income_sum_paid_amount ?? 0);
            $discount_total = (float) ($item->payroll_detail_monetary_discounts_sum_amount ?? 0);
            $employee_tac_total = (float) ($item->employee_tac_total ?? 0);
            $employer_tac_total = (float) ($item->employer_tac_total ?? 0);
            $essalud = (float) ($item->essalud ?? 0);
            $payment_details = (float) ($item->payment_details_sum ?? 0);

            $net_pay = $income_paid_total - ($discount_total + $employee_tac_total);

            // Calculate verified state
            $verified = $this->calculateVerifiedState($net_pay, $payment_details);

            // Set computed attributes
            $item->net_pay = $net_pay;
            $item->new_totals = [
                'income_paid_total' => $income_paid_total,
                'discount_total' => $discount_total,
                'employee_tac_total' => $employee_tac_total,
                'net_pay' => $net_pay,
                'employer_tac_total' => $employer_tac_total,
                'essalud' => $essalud,
            ];
            $item->verified = $verified;

            return $item;
        });
    }

    private function calculateVerifiedState(float $netPay, float $paymentDetails): array
    {
        if (empty($paymentDetails) || round($paymentDetails, 2) <= 0) {
            return ['state' => ApprovalState::PENDIENTE->value, 'amount' => $netPay];
        }

        if (round($netPay, 2) == round($paymentDetails, 2)) {
            return ['state' => ApprovalState::COMPLETADO->value, 'amount' => round($netPay - $paymentDetails, 2)];
        }

        if (round($netPay, 2) < round($paymentDetails, 2)) {
            return ['state' => ApprovalState::EXCEDIDO->value, 'amount' => round($netPay - $paymentDetails, 2)];
        }

        return ['state' => ApprovalState::PROCESO->value, 'amount' => round($netPay - $paymentDetails, 2)];
    }
}
