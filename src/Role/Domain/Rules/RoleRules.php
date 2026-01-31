<?php

namespace Src\Role\Domain\Rules;

use Src\Role\Domain\Entities\RoleEntity;
use Src\Role\Domain\Exceptions\RoleAlreadyExistsException;
use Src\Role\Domain\Repositories\RoleRepository;

class RoleRules
{
    public function __construct(
        private readonly RoleRepository $roleRepository
    ) {}

    public function validateUniqueName(string $name, ?int $excludeId = null): void
    {
        if ($this->roleRepository->exists('name', $name, $excludeId)) {
            throw new RoleAlreadyExistsException('nombre', $name);
        }
    }

    public function validateRoleForCreation(RoleEntity $role): void
    {
        $this->validateUniqueName($role->name->value());
    }

    public function validateRoleForUpdate(RoleEntity $role): void
    {
        if ($role->id === null) {
            throw new \InvalidArgumentException('El rol debe tener un ID para ser actualizado');
        }

        $this->validateUniqueName($role->name->value(), $role->id);
    }
}
