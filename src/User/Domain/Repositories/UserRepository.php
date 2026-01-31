<?php

namespace Src\User\Domain\Repositories;

use Src\User\Domain\Entities\UserEntity;

interface UserRepository
{
    public function find(int $id): ?UserEntity;

    public function findByEmail(string $email): ?UserEntity;

    public function findByDni(string $dni): ?UserEntity;

    public function all(array $filters = []): array;

    public function paginate(array $filters = [], int $perPage = 15): mixed;

    public function save(UserEntity $user): UserEntity;

    public function update(int $id, array $data): UserEntity;

    public function delete(int $id): bool;

    public function exists(string $field, string $value, ?int $excludeId = null): bool;

    public function getWithRelations(int $id, array $relations = []): ?UserEntity;

    public function linkEmployeeByDni(int $userId, string $dni): ?array;

    public function getArea(int $areaId): ?array;

    public function getAllAreas(): array;
}