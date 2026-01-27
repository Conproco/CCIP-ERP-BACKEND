<?php

declare(strict_types=1);

namespace Src\HumanResource\Infrastructure\Persistence\Payroll;

use App\Models\PayrollDetailTaxAndContribution as EloquentPayrollDetailTaxAndContribution;
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDetailTaxAndContributionRepositoryInterface;

class EloquentPayrollDetailTaxAndContributionRepository implements PayrollDetailTaxAndContributionRepositoryInterface
{
    public function findByPayrollDetailAndParam(int $payrollDetailId, int $paramId): ?object
    {
        return EloquentPayrollDetailTaxAndContribution::where('payroll_detail_id', $payrollDetailId)
            ->where('t_a_c_param_id', $paramId)
            ->first();
    }

    public function getAmount(int $payrollDetailId, int $paramId): float
    {
        $tax = $this->findByPayrollDetailAndParam($payrollDetailId, $paramId);
        return $tax ? (float) $tax->amount : 0.0;
    }
}
