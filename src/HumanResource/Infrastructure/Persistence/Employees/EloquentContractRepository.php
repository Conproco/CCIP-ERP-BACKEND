<?php

namespace Src\HumanResource\Infrastructure\Persistence\Employees;

use Src\HumanResource\Domain\Ports\Repositories\Employees\ContractRepositoryInterface;
use Src\HumanResource\Domain\Entities\Employees\Contract;
use App\Models\Contract as ContractModel;

class EloquentContractRepository implements ContractRepositoryInterface
{
    public function __construct(private ContractModel $model) {}

    public function find(int $id): ?Contract
    {
        $model = $this->model->find($id);
        if (!$model) {
            return null;
        }
        return $this->toDomainEntity($model);
    }

    public function findByEmployeeId(int $employeeId): ?Contract
    {
        $model = $this->model->where('employee_id', $employeeId)->first();
        if (!$model) {
            return null;
        }
        return $this->toDomainEntity($model);
    }

    public function save(Contract $contract): Contract
    {
        $data = $this->toPersistenceArray($contract);
        if ($contract->getId()) {
            $model = $this->model->findOrFail($contract->getId());
            $model->update($data);
        } else {
            $model = $this->model->create($data);
            $contract->setId($model->id);
        }
        return $contract;
    }

    public function update(int $id, array $data): bool
    {
        $model = $this->model->where('employee_id', $id)->first();
        if (!$model) {
            return false;
        }
        return $model->update($data);
    }

    public function delete(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }

    private function toDomainEntity(ContractModel $model): Contract
    {
        return new Contract(
            id: $model->id,
            employeeId: $model->employee_id,
            costLineId: $model->cost_line_id,
            typeContract: $model->type_contract,
            basicSalary: $model->basic_salary,
            hireDate: $model->hire_date,
            pensionType: $model->pension_type,
            stateTravelExpenses: $model->state_travel_expenses,
            amountTravelExpenses: $model->amount_travel_expenses,
            nroCuenta: $model->nro_cuenta,
            lifeLey: $model->life_ley,
            discountRemuneration: $model->discount_remuneration,
            discountSctr: $model->discount_sctr,
            state: $model->state,
            daysTaken: $model->days_taken,
            firedDate: $model->fired_date,
            personalSegment: $model->personal_segment,
            dischargeDocument: $model->discharge_document,
            cuspp: $model->cuspp,
        );
    }

    private function toPersistenceArray(Contract $contract): array
    {
        return $contract->toArray();
    }
}
