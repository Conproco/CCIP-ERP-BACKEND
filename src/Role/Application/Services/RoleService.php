<?php

namespace Src\Role\Application\Services;

use Src\Role\Application\DTOs\CreateRoleDTO;
use Src\Role\Application\DTOs\UpdateRoleDTO;
use Src\Role\Application\DTOs\RoleFiltersDTO;
use Src\Role\Domain\Entities\RoleEntity;
use Src\Role\Domain\Exceptions\RoleNotFoundException;
use Src\Role\Domain\Repositories\RoleRepository;
use Src\Role\Domain\Rules\RoleRules;

class RoleService
{
    public function __construct(
        private readonly RoleRepository $roleRepository,
        private readonly RoleRules $roleRules
    ) {}

    public function create(CreateRoleDTO $dto): RoleEntity
    {
        // Crear entidad para validación
        $roleData = $dto->toArray();
        $role = RoleEntity::fromArray($roleData);
        
        $this->roleRules->validateRoleForCreation($role);

        // Guardar con todos los datos
        return $this->roleRepository->save($role);
    }

    public function update(UpdateRoleDTO $dto): RoleEntity
    {
        $role = $this->roleRepository->find($dto->id);
        
        if (!$role) {
            throw new RoleNotFoundException($dto->id);
        }

        // Crear entidad con los nuevos datos
        $updatedRole = RoleEntity::fromArray($dto->toArray());
        
        // Validar unicidad de campos
        $this->roleRules->validateRoleForUpdate($updatedRole);

        // Preparar datos para actualizar
        $updateData = $dto->toArray();
        
        return $this->roleRepository->update($dto->id, $updateData);
    }

    public function delete(int $id): bool
    {
        $role = $this->roleRepository->find($id);
        
        if (!$role) {
            throw new RoleNotFoundException($id);
        }

        return $this->roleRepository->delete($id);
    }

    public function find(int $id): RoleEntity
    {
        $role = $this->roleRepository->find($id);
        
        if (!$role) {
            throw new RoleNotFoundException($id);
        }

        return $role;
    }

    public function findByName(string $name): ?RoleEntity
    {
        return $this->roleRepository->findByName($name);
    }

    public function all(RoleFiltersDTO $filters): array
    {
        return $this->roleRepository->all($filters->toArray());
    }

    public function paginate(RoleFiltersDTO $filters, int $perPage = 15): mixed
    {
        return $this->roleRepository->paginate($filters->toArray(), $perPage);
    }

    public function getWithFunctionalities(int $id): RoleEntity
    {
        $role = $this->roleRepository->getWithFunctionalities($id);
        
        if (!$role) {
            throw new RoleNotFoundException($id);
        }

        return $role;
    }

    public function getAllExceptAdmin(): array
    {
        $roles = $this->roleRepository->getAllExceptAdmin();
        return array_map(fn(RoleEntity $role) => $role->toArray(), $roles);
    }

    public function getModulesWithFunctionalities(): array
    {
        return $this->roleRepository->getModulesWithFunctionalities();
    }
}
