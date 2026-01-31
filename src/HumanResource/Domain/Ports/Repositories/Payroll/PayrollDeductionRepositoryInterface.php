<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Payroll;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PayrollDeductionRepositoryInterface
{
    /**
     * Get all payroll deductions paginated with filters
     */
    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a payroll deduction by ID
     */
    public function find(int $id, array $columns = ['*']): ?object;

    /**
     * Create a new payroll deduction
     */
    public function create(array $data): object;

    /**
     * Update a payroll deduction
     */
    public function update(int $id, array $data): object;

    /**
     * Delete a payroll deduction
     */
    public function delete(int $id): bool;
}
