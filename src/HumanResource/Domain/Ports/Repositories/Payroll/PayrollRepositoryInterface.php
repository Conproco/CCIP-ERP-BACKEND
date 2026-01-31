<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Payroll;

use Illuminate\Pagination\LengthAwarePaginator;

interface PayrollRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?object;

    public function findOrFail(int $id): object;

    public function updateState(int $id, bool $state): object;

    // Store payroll methods
    public function create(array $data): object;

    public function createPension(int $payrollId, array $pensionData): object;

    public function createPayrollDetail(array $data): object;

    public function createPayrollDetailIncome(int $payrollDetailId, int $incomeParamId, float $amount): object;

    public function createPayrollDetailContribution(int $payrollDetailId, int $tacParamId, float $amount): object;

    public function createPayrollDetailDiscount(int $payrollDetailId, int $discountParamId, float $amount): object;

    public function delete(int $id): void;

    public function getDiscountIdsByPayroll(int $payrollId): array;

    public function getEmployeesWithDiscounts(int $payrollId): array;
}
