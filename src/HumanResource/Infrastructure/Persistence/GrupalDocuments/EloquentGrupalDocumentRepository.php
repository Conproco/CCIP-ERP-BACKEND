<?php

namespace Src\HumanResource\Infrastructure\Persistence\GrupalDocuments;

use Src\HumanResource\Domain\Entities\GrupalDocuments\GrupalDocument as DomainGrupalDocument;
use Src\HumanResource\Domain\Ports\Repositories\GrupalDocuments\GrupalDocumentRepositoryInterface;
use App\Models\GrupalDocument as GrupalDocumentModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentGrupalDocumentRepository implements GrupalDocumentRepositoryInterface
{
    public function __construct(private GrupalDocumentModel $model)
    {
    }

    public function find(int $id): ?DomainGrupalDocument
    {
        $model = $this->model->find($id);
        if (!$model) {
            return null;
        }
        return $this->toDomainEntity($model);
    }

    public function getAllPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->orderBy('date', 'desc')->paginate($perPage);
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): object
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
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

    private function toDomainEntity(GrupalDocumentModel $model): DomainGrupalDocument
    {
        return new DomainGrupalDocument(
            id: $model->id,
            type: $model->type,
            archive: $model->archive,
            date: $model->date,
            observation: $model->observation,
            createdAt: $model->created_at?->toISOString(),
            updatedAt: $model->updated_at?->toISOString(),
        );
    }
}
