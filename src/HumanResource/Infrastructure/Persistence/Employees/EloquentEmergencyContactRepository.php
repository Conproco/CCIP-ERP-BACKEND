<?php

namespace Src\HumanResource\Infrastructure\Persistence\Employees;

use App\Models\Emergency as EmergencyModel;
use Illuminate\Database\Eloquent\Collection;
use Src\HumanResource\Domain\Entities\Employees\EmergencyContact;
use Src\HumanResource\Domain\Ports\Repositories\Employees\EmergencyContactRepositoryInterface;
use Src\Shared\Domain\ValueObjects\Telefono;

class EloquentEmergencyContactRepository implements EmergencyContactRepositoryInterface
{
    public function __construct(private EmergencyModel $model)
    {
    }

    public function find(int $id): ?EmergencyContact
    {
        $model = $this->model->find($id);
        if (!$model) {
            return null;
        }
        return $this->toDomainEntity($model);
    }

    public function findByEmployeeId(int $employeeId): Collection
    {
        $models = $this->model->where('employee_id', $employeeId)->get();
        return $models->map(fn($model) => $this->toDomainEntity($model));
    }

    public function save(EmergencyContact $contact): EmergencyContact
    {
        $data = $this->toPersistenceArray($contact);

        if ($contact->getId()) {
            $model = $this->model->findOrFail($contact->getId());
            $model->update($data);
        } else {
            $model = $this->model->create($data);
            $contact->setId($model->id);
        }

        return $contact;
    }

    public function update(EmergencyContact $contact): EmergencyContact
    {
        $model = $this->model->findOrFail($contact->getId());
        $model->update($this->toPersistenceArray($contact));
        return $contact;
    }

    public function deleteByEmployeeId(int $employeeId): void
    {
        $this->model->where('employee_id', $employeeId)->delete();
    }

    public function saveMultiple(array $contacts): void
    {
        foreach ($contacts as $contact) {
            $this->save($contact);
        }
    }

    private function toDomainEntity(EmergencyModel $model): EmergencyContact
    {
        return new EmergencyContact(
            id: $model->id,
            employeeId: $model->employee_id,
            emergencyName: $model->emergency_name,
            emergencyLastname: $model->emergency_lastname,
            emergencyRelations: $model->emergency_relations,
            emergencyPhone: new Telefono($model->emergency_phone),
        );
    }

    private function toPersistenceArray(EmergencyContact $contact): array
    {
        return $contact->toArray();
    }
}
