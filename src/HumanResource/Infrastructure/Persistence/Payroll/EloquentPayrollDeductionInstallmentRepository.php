<?php

namespace Src\HumanResource\Infrastructure\Persistence\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDeductionInstallmentRepositoryInterface;
use App\Models\PayrollDeductionInstallment as PayrollDeductionInstallmentModel;

class EloquentPayrollDeductionInstallmentRepository implements PayrollDeductionInstallmentRepositoryInterface
{
    public function __construct(private PayrollDeductionInstallmentModel $model)
    {
    }

    public function getByDeductionId(int $deductionId, array $filters = []): object
    {
        $query = $this->model->where('payroll_deduction_id', $deductionId);

        if (!empty($filters['searchQuery'])) {
            $searchQuery = $filters['searchQuery'];
            // Installments might not have searchable fields directly, but if needed:
            // $query->where('some_field', 'like', "%{$searchQuery}%");
        }

        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        return $query->get();
    }

    public function find(int $id, array $columns = ['*']): ?object
    {
        // Ensure id is always included for proper model binding
        if ($columns !== ['*'] && !in_array('id', $columns)) {
            $columns[] = 'id';
        }
        return $this->model->select($columns)->find($id);
    }

    public function update(int $id, array $data): object
    {
        $installment = $this->model->findOrFail($id);
        $installment->update($data);
        return $installment;
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function deleteByDeductionId(int $deductionId): bool
    {
        return $this->model->where('payroll_deduction_id', $deductionId)->delete() > 0;
    }

    public function revertByDiscountIds(array $discountIds): void
    {
        $installments = $this->model->whereIn('payroll_detail_monetary_discount_id', $discountIds)->get();

        foreach ($installments as $installment) {
            $expenseId = $installment->general_expense_id;

            // 1. Revert state and UNLINK the GeneralExpense (Crucial step for FK check)
            $installment->update([
                'payment_status' => 'Pendiente',
                'payroll_detail_monetary_discount_id' => null,
                'general_expense_id' => null
            ]);

            // 2. Now that the link is gone, we can safely delete the GeneralExpense
            if ($expenseId) {
                \App\Models\GeneralExpense::where('id', $expenseId)->delete();
            }
        }
    }
}
