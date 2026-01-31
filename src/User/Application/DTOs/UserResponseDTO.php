<?php

namespace Src\User\Application\DTOs;

use Src\User\Domain\Entities\UserEntity;

class UserResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $dni,
        public readonly string $phone,
        public readonly string $platform,
        public readonly ?int $roleId,
        public readonly ?array $funcionalities= [],
        public readonly ?array $permissions= [],
        public readonly ?int $areaId,
        public readonly ?array $role = null,
        public readonly ?array $area = null,
    ) {}

    public static function fromEntity(UserEntity $entity, ?array $role = null, ?array $area = null, ?array $funcionalities = [] , ?array $permissions = []): self
    {
        return new self(
            id: $entity->id,
            name: $entity->name,
            email: $entity->email->value(),
            dni: $entity->dni->value(),
            phone: $entity->phone->value(),
            platform: $entity->platform,
            roleId: $entity->roleId,
            funcionalities:$funcionalities,
            permissions:$permissions,   
            areaId: $entity->areaId,
            role: $role,
            area: $area,
        );
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'dni' => $this->dni,
            'phone' => $this->phone,
            'platform' => $this->platform,
            'role_id' => $this->roleId,
            'funcionalities' => $this->funcionalities,
            'permissions' => $this->permissions,
            'area_id' => $this->areaId,
        ];

        if ($this->role !== null) {
            $data['role'] = $this->role;
        }

        if ($this->area !== null) {
            $data['area'] = $this->area;
        }

        return $data;
    }
}
