<?php

namespace Src\Role\Domain\Entities;

use Src\Role\Domain\ValueObjects\RoleName;
use Src\Role\Domain\ValueObjects\RoleDescription;

class RoleEntity
{
    public function __construct(
        public readonly ?int $id,
        public RoleName $name,
        public RoleDescription $description,
        public array $functionalities = [],
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int)$data['id'] : null,
            name: new RoleName($data['name']),
            description: new RoleDescription($data['description'] ?? null),
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

    public function changeName(RoleName $name): void
    {
        $this->name = $name;
    }

    public function changeDescription(RoleDescription $description): void
    {
        $this->description = $description;
    }

    public function updateFunctionalities(array $functionalities): void
    {
        $this->functionalities = $functionalities;
    }

    public function addFunctionality(int $functionalityId): void
    {
        if (!in_array($functionalityId, $this->functionalities)) {
            $this->functionalities[] = $functionalityId;
        }
    }

    public function removeFunctionality(int $functionalityId): void
    {
        $this->functionalities = array_filter(
            $this->functionalities,
            fn($id) => $id !== $functionalityId
        );
    }
}
