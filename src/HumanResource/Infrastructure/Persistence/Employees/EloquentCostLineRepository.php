<?php

namespace Src\HumanResource\Infrastructure\Persistence\Employees;

use Src\HumanResource\Domain\Entities\Employees\CostLine as DomainCostLine;
use Src\HumanResource\Domain\Ports\Repositories\Employees\CostLineRepositoryInterface;
use App\Models\CostLine as CostLineModel;
use Illuminate\Database\Eloquent\Collection;

class EloquentCostLineRepository implements CostLineRepositoryInterface
{
    public function __construct(private CostLineModel $model) {}

    public function find(int $id): ?DomainCostLine
    {
        $model = $this->model->find($id);
        if (!$model) {
            return null;
        }
        return $this->toDomainEntity($model);
    }

    public function getAll(): Collection
    {
        return $this->model->get();
    }

    public function findByName(string $name): ?DomainCostLine
    {
        $model = $this->model->where('name', $name)->first();
        if (!$model) {
            return null;
        }
        return $this->toDomainEntity($model);
    }

    private function toDomainEntity(CostLineModel $model): DomainCostLine
    {
        return new DomainCostLine(
            id: $model->id,
            name: $model->name,
        );
    }
}
