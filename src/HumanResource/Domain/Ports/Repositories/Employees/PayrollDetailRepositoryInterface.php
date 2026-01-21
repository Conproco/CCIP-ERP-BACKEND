<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Employees;

interface PayrollDetailRepositoryInterface
{
    /**
     * Find the latest payroll detail for an employee
     */
    public function findLatestByEmployeeId(int $employeeId): ?object;

    /**
     * Update fired data (fired_date, days_taken) for a payroll detail
     */
    public function updateFiredData(int $id, string $firedDate, int $daysTaken): bool;
}
