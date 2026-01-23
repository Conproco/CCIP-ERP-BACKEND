<?php

namespace Src\HumanResource\Infrastructure\Persistence\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollRepositoryInterface;
use App\Models\Payroll as PayrollModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentPayrollRepository implements PayrollRepositoryInterface
{
    public function __construct(private PayrollModel $model)
    {
    }

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        $payrolls = $this->model
            ->orderBy('month', 'desc')
            ->paginate($perPage);

        // Add calculated total_amount attribute
        $payrolls->getCollection()->each(function ($payroll) {
            $payroll->setAppends(['total_amount']);
        });

        return $payrolls;
    }

    public function find(int $id): ?object
    {
        return $this->model->find($id);
    }
}
