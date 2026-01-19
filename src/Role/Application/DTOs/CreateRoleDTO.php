<?php

namespace Src\Role\Application\DTOs;

use Src\Role\Domain\ValueObjects\RoleName;
use Src\Role\Domain\ValueObjects\RoleDescription;

final class CreateRoleDTO
{
    public function __construct(
        public readonly RoleName $name,
        public readonly RoleDescription $description,
        public readonly array $functionalities = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: new RoleName($data['name']),
            description: new RoleDescription($data['description'] ?? null),
            functionalities: $data['functionalities'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name->value(),
            'description' => $this->description->value(),
            'functionalities' => $this->functionalities,
        ];
    }
}
