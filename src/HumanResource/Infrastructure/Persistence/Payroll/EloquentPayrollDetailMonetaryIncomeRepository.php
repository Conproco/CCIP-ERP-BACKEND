<?php

declare(strict_types=1);

namespace Src\HumanResource\Infrastructure\Persistence\Payroll;

use App\Models\PayrollDetailMonetaryIncome as EloquentPayrollDetailMonetaryIncome;
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDetailMonetaryIncomeRepositoryInterface;

class EloquentPayrollDetailMonetaryIncomeRepository implements PayrollDetailMonetaryIncomeRepositoryInterface
{
    public function findByPayrollDetailAndParam(int $payrollDetailId, int $incomeParamId): ?object
    {
        return EloquentPayrollDetailMonetaryIncome::where('payroll_detail_id', $payrollDetailId)
            ->where('income_param_id', $incomeParamId)
            ->first();
    }

    public function getPaidAmount(int $payrollDetailId, int $incomeParamId): float
    {
        $income = $this->findByPayrollDetailAndParam($payrollDetailId, $incomeParamId);
        return $income ? (float) $income->paid_amount : 0.0;
    }
}
