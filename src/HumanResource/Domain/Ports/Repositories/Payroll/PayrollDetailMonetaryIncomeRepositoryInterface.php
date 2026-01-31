<?php

declare(strict_types=1);

namespace Src\HumanResource\Domain\Ports\Repositories\Payroll;

interface PayrollDetailMonetaryIncomeRepositoryInterface
{
    /**
     * Find monetary income by payroll detail and income param
     */
    public function findByPayrollDetailAndParam(int $payrollDetailId, int $incomeParamId): ?object;

    /**
     * Get paid amount for a specific payroll detail and income param
     */
    public function getPaidAmount(int $payrollDetailId, int $incomeParamId): float;
}
