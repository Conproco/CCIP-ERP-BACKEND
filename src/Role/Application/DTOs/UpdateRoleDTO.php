<?php

namespace Src\Role\Application\DTOs;

use Src\Role\Domain\ValueObjects\RoleName;
use Src\Role\Domain\ValueObjects\RoleDescription;

final class UpdateRoleDTO
{
    public function __construct(
        public readonly int $id,
        public readonly RoleName $name,
        public readonly RoleDescription $description,
        public readonly array $functionalities = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            name: new RoleName($data['name']),
            description: new RoleDescription($data['description']),
            functionalities: $data['functionalities'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name->value(),
            'description' => $this->description->value(),
            'functionalities' => $this->functionalities,
        ];
    }
}
