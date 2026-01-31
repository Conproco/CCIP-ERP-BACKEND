<?php

namespace Src\Role\Domain\Repositories;

use Src\Role\Domain\Entities\RoleEntity;

interface RoleRepository
{
    
    public function find(int $id): ?RoleEntity;

    public function findByName(string $name): ?RoleEntity;

    public function all(array $filters = []): array;
   
    public function paginate(array $filters = [], int $perPage = 15): mixed;

    public function save(RoleEntity $role): RoleEntity;

    public function update(int $id, array $data): RoleEntity;
  
    public function delete(int $id): bool;

    public function exists(string $field, string $value, ?int $excludeId = null): bool;

    public function getWithFunctionalities(int $id): ?RoleEntity;

    public function getAllExceptAdmin(): array;

    public function getModulesWithFunctionalities(): array;
}
