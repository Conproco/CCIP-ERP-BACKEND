<?php

declare(strict_types=1);

namespace Src\HumanResource\Infrastructure\Persistence\Payroll;

use App\Models\PayrollDetailExpense;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Src\HumanResource\Domain\Enums\Payroll\PayrollExpenseType;
use Src\HumanResource\Domain\Enums\Payroll\PayrollDocType;
use Src\HumanResource\Domain\Enums\Payroll\PayrollExpenseStateType;
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDetailExpenseRepositoryInterface;

class EloquentPayrollDetailExpenseRepository implements PayrollDetailExpenseRepositoryInterface
{
    public function __construct(private PayrollDetailExpense $model)
    {
    }

    public function create(array $data): PayrollDetailExpense
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): PayrollDetailExpense
    {
        $expense = $this->model->findOrFail($id);
        $expense->update($data);
        return $expense->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    public function find(int $id): ?PayrollDetailExpense
    {
        return $this->model->find($id);
    }

    public function getByPayrollId(int $payrollId): LengthAwarePaginator
    {
        return $this->model
            ->whereHas('payroll_detail', fn($q) => $q->where('payroll_id', $payrollId))
            ->paginate(20)
            ->withQueryString();
    }

    public function getByPayrollDetailId(int $payrollDetailId): Collection
    {
        return $this->model->where('payroll_detail_id', $payrollDetailId)->get();
    }

    public function filter(int $payrollId, array $filters): Collection
    {
        $query = $this->model
            ->with('general_expense', 'payroll_detail')
            ->whereHas('payroll_detail', fn($q) => $q->where('payroll_id', $payrollId));

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('doc_number', 'like', "%{$search}%")
                    ->orWhere('operation_number', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        // Document date filters
        if (!empty($filters['docNoDate']) && filter_var($filters['docNoDate'], FILTER_VALIDATE_BOOLEAN)) {
            $query->whereNull('doc_date');
        }
        if (!empty($filters['docStartDate'])) {
            $query->where('doc_date', '>=', $filters['docStartDate']);
        }
        if (!empty($filters['docEndDate'])) {
            $query->where('doc_date', '<=', $filters['docEndDate']);
        }

        // Operation date filters
        if (!empty($filters['opNoDate']) && filter_var($filters['opNoDate'], FILTER_VALIDATE_BOOLEAN)) {
            $query->whereNull('operation_date');
        }
        if (!empty($filters['opStartDate'])) {
            $query->where('operation_date', '>=', $filters['opStartDate']);
        }
        if (!empty($filters['opEndDate'])) {
            $query->where('operation_date', '<=', $filters['opEndDate']);
        }

        // Expense type filter
        if (!empty($filters['selectedExpenseTypes']) && is_array($filters['selectedExpenseTypes'])) {
            if (count($filters['selectedExpenseTypes']) < PayrollExpenseType::count()) {
                $query->whereIn('expense_type', $filters['selectedExpenseTypes']);
            }
        }

        // Document type filter
        if (!empty($filters['selectedDocTypes']) && is_array($filters['selectedDocTypes'])) {
            if (count($filters['selectedDocTypes']) < PayrollDocType::count()) {
                $query->whereIn('type_doc', $filters['selectedDocTypes']);
            }
        }

        $results = $query->get();

        // Append attributes
        $results->each(function ($item) {
            if ($item->relationLoaded('payroll_detail')) {
                $item->payroll_detail->setAppends([]);
            }
            $item->setAppends(['real_state']);
        });

        // Post-filter by state (accessor-based filtering)
        if (!empty($filters['selectedStateTypes']) && is_array($filters['selectedStateTypes'])) {
            if (count($filters['selectedStateTypes']) < PayrollExpenseStateType::count()) {
                $results = $results->filter(
                    fn($item) => in_array($item->real_state, $filters['selectedStateTypes'])
                )->values();
            }
        }

        return $results;
    }

    public function updateBatch(array $ids, array $data): Collection
    {
        $this->model->whereIn('id', $ids)->update($data);
        return $this->model->whereIn('id', $ids)->get();
    }
}
