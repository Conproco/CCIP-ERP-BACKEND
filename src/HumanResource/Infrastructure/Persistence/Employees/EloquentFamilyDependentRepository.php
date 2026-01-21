<?php

namespace Src\HumanResource\Infrastructure\Persistence\Employees;

use App\Models\Family as FamilyModel;
use Illuminate\Database\Eloquent\Collection;
use Src\HumanResource\Domain\Entities\Employees\FamilyDependent;
use Src\HumanResource\Domain\Ports\Repositories\Employees\FamilyDependentRepositoryInterface;

class EloquentFamilyDependentRepository implements FamilyDependentRepositoryInterface
{
    public function  __construct(private FamilyModel $model) {}
    
    public function find(int $id): ?FamilyDependent
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
        return $models->map(fn ($model) => $this->toDomainEntity($model));
    }
    
    public function save(FamilyDependent $dependent): FamilyDependent
    {
        $data = $this->toPersistenceArray($dependent);
        
        if ($dependent->getId()) {
            $model = $this->model->findOrFail($dependent->getId());
            $model->update($data);
        } else {
            $model = $this->model->create($data);
            $dependent->setId($model->id);
        }
        
        return $dependent;
    }
    
    public function update(FamilyDependent $dependent): FamilyDependent
    {
        $model = $this->model->findOrFail($dependent->getId());
        $model->update($this->toPersistenceArray($dependent));
        return $dependent;
    }

    public function deleteByEmployeeId(int $employeeId): void
    {
        $models = $this->model->where('employee_id', $employeeId)->get();
        $models->each->delete();
    }
    
    public function saveMultiple(array $dependents): void
    {
        foreach ($dependents as $dependent) {
            $this->save($dependent);
        }
    }
    
    private function toDomainEntity(FamilyModel $model): FamilyDependent
    {
        return new FamilyDependent(
            id: $model->id,
            employeeId: $model->employee_id,
            familyName: $model->family_name,
            familyLastname: $model->family_lastname,
            familyRelation: $model->family_relation,
            familyEducation: $model->family_education,
        );
    }
    
    private function toPersistenceArray(FamilyDependent $dependent): array
    {
        return $dependent->toArray();
    }
}