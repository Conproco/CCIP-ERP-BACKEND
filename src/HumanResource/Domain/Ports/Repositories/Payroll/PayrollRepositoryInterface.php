<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Payroll;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PayrollRepositoryInterface
{
    /**
     * Get all payrolls paginated, ordered by month descending
     */
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a payroll by ID
     */
    public function find(int $id): ?object;

    /**
     * Find a payroll by ID or fail with exception
     */
    public function findOrFail(int $id): object;

    /**
     * Update payroll state
     */
    public function updateState(int $id, bool $state): object;
}
