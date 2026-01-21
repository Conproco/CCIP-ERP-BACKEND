<?php

namespace Src\HumanResource\Infrastructure\Persistence\ExternalEmployees;

use Src\HumanResource\Domain\Entities\ExternalEmployees\ExternalEmployee as DomainExternalEmployee;
use Src\HumanResource\Domain\Ports\Repositories\ExternalEmployees\ExternalEmployeeRepositoryInterface;
use App\Models\ExternalEmployee as ExternalEmployeeModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentExternalEmployeeRepository implements ExternalEmployeeRepositoryInterface
{
    public function __construct(private ExternalEmployeeModel $model)
    {
    }

    public function find(int $id): ?DomainExternalEmployee
    {
        $model = $this->model->find($id);
        if (!$model) {
            return null;
        }
        return $this->toDomainEntity($model);
    }

    public function findWithRelations(int $id): ?object
    {
        return $this->model->with('cost_line')->find($id);
    }

    public function getAll(): array
    {
        return $this->model->all()->map(fn($model) => $this->toDomainEntity($model))->toArray();
    }

    public function getAllPaginateWithRelations(array $filters = []): LengthAwarePaginator
    {
        $data = $this->model->with('cost_line')
            ->when(!empty($filters['searchQuery']), function ($query) use ($filters) {
                $searchQuery = $filters['searchQuery'];
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('name', 'like', "%{$searchQuery}%")
                        ->orWhere('lastname', 'like', "%{$searchQuery}%")
                        ->orWhere('dni', 'like', "%{$searchQuery}%");
                });
            })
            ->when(!empty($filters['cost_line']), function ($query) use ($filters) {
                $cost_line = $filters['cost_line'];
                $query->whereHas('cost_line', function ($q) use ($cost_line) {
                    $q->whereIn('name', $cost_line);
                });
            });
        return $data->paginate()->withQueryString();
    }

    public function create(array $data): object
    {
        $model = $this->model->create($data);
        $model->load('cost_line');
        return $model;
    }

    public function update(int $id, array $data): object
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        $model->load('cost_line');
        return $model;
    }

    public function delete(int $id): bool
    {
        $model = $this->model->find($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    private function toDomainEntity(ExternalEmployeeModel $model): DomainExternalEmployee
    {
        return new DomainExternalEmployee(
            id: $model->id,
            name: $model->name,
            lastname: $model->lastname,
            costLineId: $model->cost_line_id,
            croppedImage: $model->cropped_image,
            gender: $model->gender,
            address: $model->address,
            birthdate: $model->birthdate,
            dni: $model->dni,
            email: $model->email,
            emailCompany: $model->email_company,
            phone1: $model->phone1,
            salary: $model->salary,
            sctr: $model->sctr,
            curriculumVitae: $model->curriculum_vitae,
            lPolicy: $model->l_policy,
            sctrExpDate: $model->sctr_exp_date,
            policyExpDate: $model->policy_exp_date,
        );
    }
}

