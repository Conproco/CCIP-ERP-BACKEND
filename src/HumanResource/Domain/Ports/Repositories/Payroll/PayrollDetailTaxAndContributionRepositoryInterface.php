<?php

declare(strict_types=1);

namespace Src\HumanResource\Domain\Ports\Repositories\Payroll;

interface PayrollDetailTaxAndContributionRepositoryInterface
{
    /**
     * Find tax and contribution by payroll detail and param
     */
    public function findByPayrollDetailAndParam(int $payrollDetailId, int $paramId): ?object;

    /**
     * Get tax amount for a specific payroll detail and tax param
     */
    public function getAmount(int $payrollDetailId, int $paramId): float;
}
