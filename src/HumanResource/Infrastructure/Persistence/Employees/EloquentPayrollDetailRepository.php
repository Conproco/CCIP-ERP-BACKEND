<?php

namespace Src\HumanResource\Infrastructure\Persistence\Employees;

use Src\HumanResource\Domain\Ports\Repositories\Employees\PayrollDetailRepositoryInterface;
use App\Models\PayrollDetail as PayrollDetailModel;

class EloquentPayrollDetailRepository implements PayrollDetailRepositoryInterface
{
    public function __construct(private PayrollDetailModel $model)
    {
    }

    public function findLatestByEmployeeId(int $employeeId): ?object
    {
        return $this->model->where('employee_id', $employeeId)
            ->latest('created_at')
            ->first();
    }

    public function updateFiredData(int $id, string $firedDate, int $daysTaken): bool
    {
        $payrollDetail = $this->model->find($id);

        if (!$payrollDetail) {
            return false;
        }

        return $payrollDetail->update([
            'fired_date' => $firedDate,
            'days_taken' => $daysTaken,
        ]);
    }
}
