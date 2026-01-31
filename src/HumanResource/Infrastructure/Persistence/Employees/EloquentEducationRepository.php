<?php

namespace Src\HumanResource\Infrastructure\Persistence\Employees;

use App\Models\Education as EducationModel;
use Src\HumanResource\Domain\Entities\Employees\Education;
use Src\HumanResource\Domain\Ports\Repositories\Employees\EducationRepositoryInterface;

class EloquentEducationRepository implements EducationRepositoryInterface
{
    public function __construct(private EducationModel $model) {}

    public function find(int $id): ?Education
    {
        $model = $this->model->find($id);
        if (!$model) {
            return null;
        }
        return $this->toDomainEntity($model);
    }

    public function findByEmployeeId(int $employeeId): ?Education
    {
        $model = $this->model->where('employee_id', $employeeId)->first();
        if (!$model) {
            return null;
        }
        return $this->toDomainEntity($model);
    }

    public function save(Education $education): Education
    {
        $data = $this->toPersistenceArray($education);
        if ($education->getId()) {
            $model = $this->model->findOrFail($education->getId());
            $model->update($data);
        } else {
            $model = $this->model->create($data);
            $education->setId($model->id);
        }
        return $education;
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

    private function toDomainEntity(EducationModel $model): Education
    {
        return new Education(
            id: $model->id,
            employeeId: $model->employee_id,
            educationLevel: $model->education_level,
            educationStatus: $model->education_status,
            specialization: $model->specialization,
            curriculumVitae: $model->curriculum_vitae,
        );
    }

    private function toPersistenceArray(Education $education): array
    {
        return $education->toArray();
    }
}