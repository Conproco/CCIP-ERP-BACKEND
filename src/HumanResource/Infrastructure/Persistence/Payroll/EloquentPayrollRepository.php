<?php

namespace Src\HumanResource\Infrastructure\Persistence\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollRepositoryInterface;
use App\Models\Payroll as PayrollModel;
use App\Models\Pension;
use App\Models\PayrollDetail;
use App\Models\PayrollDetailMonetaryIncome;
use App\Models\PayrollDetailMonetaryDiscount;
use App\Models\PayrollDetailTaxAndContribution;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EloquentPayrollRepository implements PayrollRepositoryInterface
{
    public function __construct(private PayrollModel $model)
    {
    }

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        $totals = DB::table('payrolls')
            ->leftJoin('payroll_details as pd', 'payrolls.id', '=', 'pd.payroll_id')
            ->leftJoin('payroll_detail_monetary_incomes as pmi', 'pd.id', '=', 'pmi.payroll_detail_id')
            ->leftJoin('payroll_detail_monetary_discounts as pmd', 'pd.id', '=', 'pmd.payroll_detail_id')
            ->leftJoin('payroll_detail_tax_and_contributions as ptc', 'pd.id', '=', 'ptc.payroll_detail_id')
            ->leftJoin('tax_and_contribution_params as tcp', function ($join) {
                $join->on('tcp.id', '=', 'ptc.t_a_c_param_id')
                    ->where('tcp.type', '=', 'employee');
            })
            ->select([
                'payrolls.id',
                'payrolls.month',
                'payrolls.state',
                'payrolls.sctr_p',
                'payrolls.sctr_s',
                'payrolls.created_at',
                'payrolls.updated_at',
                DB::raw('COALESCE(SUM(pmi.paid_amount), 0) - COALESCE(SUM(pmd.amount), 0) - COALESCE(SUM(ptc.amount), 0) as total_amount')
            ])
            ->groupBy('payrolls.id', 'payrolls.month', 'payrolls.state', 'payrolls.sctr_p', 'payrolls.sctr_s', 'payrolls.created_at', 'payrolls.updated_at')
            ->orderBy('payrolls.month', 'desc');

        $page = request()->get('page', 1);
        $total = DB::table('payrolls')->count();
        $results = $totals->skip(($page - 1) * $perPage)->take($perPage)->get();

        return new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function find(int $id): ?object
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): object
    {
        return $this->model->findOrFail($id);
    }

    public function updateState(int $id, bool $state): object
    {
        $payroll = $this->model->findOrFail($id);
        $payroll->update(['state' => $state]);
        return $payroll->fresh();
    }

    // ==================== Store Payroll Methods ====================

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function createPension(int $payrollId, array $pensionData): object
    {
        return Pension::create([
            'payroll_id' => $payrollId,
            'type' => $pensionData['type'],
            'commission_flow' => $pensionData['commission_flow'],
            'annual_commission_balance' => $pensionData['annual_commission_balance'],
            'insurance_premium' => $pensionData['insurance_premium'],
            'mandatory_contribution' => $pensionData['mandatory_contribution'],
        ]);
    }

    public function createPayrollDetail(array $data): object
    {
        return PayrollDetail::create($data);
    }

    public function createPayrollDetailIncome(int $payrollDetailId, int $incomeParamId, float $amount): object
    {
        return PayrollDetailMonetaryIncome::create([
            'payroll_detail_id' => $payrollDetailId,
            'income_param_id' => $incomeParamId,
            'accrued_amount' => $amount,
            'paid_amount' => $amount,
        ]);
    }

    public function createPayrollDetailContribution(int $payrollDetailId, int $tacParamId, float $amount): object
    {
        return PayrollDetailTaxAndContribution::create([
            'payroll_detail_id' => $payrollDetailId,
            't_a_c_param_id' => $tacParamId,
            'amount' => $amount,
        ]);
    }

    public function createPayrollDetailDiscount(int $payrollDetailId, int $discountParamId, float $amount): object
    {
        return PayrollDetailMonetaryDiscount::create([
            'payroll_detail_id' => $payrollDetailId,
            'discount_param_id' => $discountParamId,
            'amount' => $amount,
        ]);
    }
}
