<?php

namespace Src\HumanResource\Infrastructure\Persistence\Employees;

use Src\HumanResource\Domain\Entities\Employees\Address;
use Src\HumanResource\Domain\Ports\Repositories\Employees\AddressRepositoryInterface;
use App\Models\Address as AddressModel;

class EloquentAddressRepository implements AddressRepositoryInterface
{
    public function __construct(private AddressModel $model) {}

    public function find(int $id): ?Address
    {
        $model = $this->model->find($id);
        if (!$model) {
            return null;
        }
        return $this->toDomainEntity($model);
    }

    public function findByEmployeeId(int $employeeId): ?Address
    {
        $model = $this->model->where('employee_id', $employeeId)->first();
        if (!$model) {
            return null;
        }
        return $this->toDomainEntity($model);
    }

    public function save(Address $address): Address
    {
        $data = $this->toPersistenceArray($address);
        if ($address->getId()) {
            $model = $this->model->findOrFail($address->getId());
            $model->update($data);
        } else {
            $model = $this->model->create($data);
            $address->setId($model->id);
        }
        return $address;
    }

    public function update(int $employeeId, array $data): bool
    {
        $model = $this->model->where('employee_id', $employeeId)->first();
        if (!$model) {
            return false;
        }
        return $model->update($data);
    }

    public function delete(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }

    private function toDomainEntity(AddressModel $model): Address
    {
        return new Address(
            id: $model->id,
            employeeId: $model->employee_id,
            streetAddress: $model->street_address,
            department: $model->department,
            province: $model->province,
            district: $model->district,
        );
    }

    private function toPersistenceArray(Address $address): array
    {
        return $address->toArray();
    }
}
