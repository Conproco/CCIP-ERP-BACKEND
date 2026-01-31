<?php

namespace Src\Role\Application\DTOs;

use Src\Role\Domain\Entities\RoleEntity;

class RoleResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly array $functionalities,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null,
    ) {}

    public static function fromEntity(RoleEntity $entity): self
    {
        return new self(
            id: $entity->id,
            name: $entity->name->value(),
            description: $entity->description->value(),
            functionalities: $entity->functionalities,
            createdAt: $entity->created_at,
            updatedAt: $entity->updated_at,
        );
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'functionalities' => $this->functionalities,
        ];

        if ($this->createdAt !== null) {
            $data['created_at'] = $this->createdAt;
        }

        if ($this->updatedAt !== null) {
            $data['updated_at'] = $this->updatedAt;
        }

        return $data;
    }
}
