<?php

namespace Src\Product\Application\DTOs;

use Src\Product\Domain\Entities\ProductEntity;

class ProductResponseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly ?string $primaryCode,
        public readonly ?string $secondaryCode,
        public readonly ?string $scType,
        public readonly int $unitId,
        public readonly string $unitName,
        public readonly ?string $deletedAt,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
    ) {}

    public static function fromEntity(ProductEntity $entity): self
    {
        return new self(
            id: $entity->id,
            name: $entity->name,
            description: $entity->description,
            primaryCode: $entity->primaryCode,
            secondaryCode: $entity->secondaryCode,
            scType: $entity->scType,
            unitId: $entity->unitId,
            unitName: $entity->unitName ?? 'Sin unidad',
            deletedAt: $entity->deletedAt,
            createdAt: $entity->createdAt,
            updatedAt: $entity->updatedAt,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'primary_code' => $this->primaryCode,
            'secondary_code' => $this->secondaryCode,
            'sc_type' => $this->scType,
            'unit_id' => $this->unitId,
            'unit_name' => $this->unitName,
            'deleted_at' => $this->deletedAt,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
