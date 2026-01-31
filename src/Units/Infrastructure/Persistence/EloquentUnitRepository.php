<?php

namespace Src\Units\Infrastructure\Persistence;

use Src\Units\Domain\Entities\UnitEntity;
use Src\Units\Domain\Repositories\UnitRepository;
use App\Models\Unit as UnitModel;

class EloquentUnitRepository implements UnitRepository
{
    public function find(int $id): ?UnitEntity
    {
        $model = UnitModel::find($id);
        
        if (!$model) {
            return null;
        }

        return new UnitEntity(
            id: $model->id,
            name: $model->name ?? '',
        );
    }

    public function all(): array
    {
        return UnitModel::all()
            ->map(fn ($unit) => new UnitEntity(
                $unit->id,
                $unit->name
            ))
            ->toArray();
    }
}
