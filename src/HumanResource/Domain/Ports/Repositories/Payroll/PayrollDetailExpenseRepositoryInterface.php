<?php

declare(strict_types=1);

namespace Src\HumanResource\Domain\Ports\Repositories\Payroll;

use App\Models\PayrollDetailExpense;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PayrollDetailExpenseRepositoryInterface
{
    public function create(array $data): PayrollDetailExpense;

    public function update(int $id, array $data): PayrollDetailExpense;

    public function delete(int $id): bool;

    public function find(int $id): ?PayrollDetailExpense;

    public function getByPayrollId(int $payrollId): LengthAwarePaginator;

    public function getByPayrollDetailId(int $payrollDetailId): Collection;

    public function filter(int $payrollId, array $filters): Collection;

    public function updateBatch(array $ids, array $data): Collection;
}
