<?php

namespace Src\HumanResource\Infrastructure\Persistence\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDeductionRepositoryInterface;
use App\Models\PayrollDeduction as PayrollDeductionModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentPayrollDeductionRepository implements PayrollDeductionRepositoryInterface
{
    public function __construct(private PayrollDeductionModel $model)
    {
    }

    public function getAllPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with('employee')->orderBy('created_at', 'desc');

        // Apply filters
        if (!empty($filters['searchquery'])) {
            $searchQuery = $filters['searchquery'];
            $query->where(function ($q) use ($searchQuery) {
                // Search in PayrollDeduction fields
                $q->where('operation_number', 'like', "%{$searchQuery}%")
                    // Search in Employee fields
                    ->orWhereHas('employee', function ($employeeQuery) use ($searchQuery) {
                        $employeeQuery->where('name', 'like', "%{$searchQuery}%")
                            ->orWhere('lastname', 'like', "%{$searchQuery}%")
                            ->orWhere('dni', 'like', "%{$searchQuery}%");
                    });
            });
        }

        if (!empty($filters['reason'])) {
            $query->where('reason', $filters['reason']);
        }

        if (!empty($filters['opStartDate']) && !empty($filters['opEndDate'])) {
            $query->whereBetween('operation_date', [$filters['opStartDate'], $filters['opEndDate']]);
        }

        if (!empty($filters['opNoDate'])) {
            $query->whereNull('operation_date');
        }

        $deductions = $query->paginate($perPage);

        // Add calculated attributes
        $deductions->getCollection()->each(function ($deduction) {
            $deduction->setAppends(['total_amount', 'status']);
        });

        return $deductions;
    }

    public function find(int $id, array $columns = ['*']): ?object
    {
        return $this->model->select($columns)->find($id);
    }

    public function create(array $data): object
    {
        $deduction = $this->model->create($data);
        $deduction->load('employee');
        $deduction->setAppends(['total_amount', 'status']);
        return $deduction;
    }

    public function update(int $id, array $data): object
    {
        $deduction = $this->model->findOrFail($id);
        $deduction->update($data);
        $deduction->load('employee');
        $deduction->setAppends(['total_amount', 'status']);
        return $deduction;
    }

    public function delete(int $id): bool
    {
        $deduction = $this->model->findOrFail($id);
        return $deduction->delete();
    }
}
