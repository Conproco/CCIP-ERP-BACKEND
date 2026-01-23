<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Payroll;

interface PayrollDeductionInstallmentRepositoryInterface
{
    /**
     * Get installments by deduction ID with filters
     */
    public function getByDeductionId(int $deductionId, array $filters = []): object;

    /**
     * Find an installment by ID
     */
    public function find(int $id, array $columns = ['*']): ?object;

    /**
     * Update an installment
     */
    public function update(int $id, array $data): object;

    /**
     * Create a new payroll deduction installment
     */
    public function create(array $data): object;

    /**
     * Delete all installments for a deduction
     */
    public function deleteByDeductionId(int $deductionId): bool;
}
