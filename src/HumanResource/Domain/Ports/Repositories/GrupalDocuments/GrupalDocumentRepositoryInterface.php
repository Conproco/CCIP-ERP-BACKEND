<?php

namespace Src\HumanResource\Domain\Ports\Repositories\GrupalDocuments;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface GrupalDocumentRepositoryInterface
{
    public function find(int $id): ?object;

    public function getAllPaginated(int $perPage = 20): LengthAwarePaginator;

    public function create(array $data): object;

    public function update(int $id, array $data): object;

    public function delete(int $id): bool;
}
