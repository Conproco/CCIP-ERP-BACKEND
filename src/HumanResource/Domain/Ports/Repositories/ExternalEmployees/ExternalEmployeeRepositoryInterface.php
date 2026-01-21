<?php

namespace Src\HumanResource\Domain\Ports\Repositories\ExternalEmployees;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ExternalEmployeeRepositoryInterface
{
    public function find(int $id): ?object;

    public function findWithRelations(int $id): ?object;

    public function getAll(): array;

    public function getAllPaginateWithRelations(array $filters = []): LengthAwarePaginator;

    public function create(array $data): object;

    public function update(int $id, array $data): object;

    public function delete(int $id): bool;
}

