<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Payroll;

use Illuminate\Support\Collection;

interface PayrollDetailRepositoryInterface
{
    /**
     * Get payroll details by payroll ID with optional filters
     */
    public function getDetailsByPayrollWithFilters(
        int $payrollId,
        ?string $search = null,
        ?array $pensionTypes = null
    ): Collection;
}
