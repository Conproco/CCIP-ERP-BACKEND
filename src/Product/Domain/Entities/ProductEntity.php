<?php

namespace Src\Product\Domain\Entities;

class ProductEntity
{
    public function __construct(
        public readonly ?int $id,
        public string $name,
        public ?string $description,
        public ?string $primaryCode,
        public ?string $secondaryCode,
        public ?string $scType,
        public int $unitId,
        public ?string $unitName = null,
        public ?string $deletedAt = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int)$data['id'] : null,
            name: $data['name'],
            description: $data['description'] ?? null,
            primaryCode: $data['primary_code'] ?? null,
            secondaryCode: $data['secondary_code'] ?? null,
            scType: $data['sc_type'] ?? null,
            unitId: (int)$data['unit_id'],
            unitName: $data['unit_name'] ?? null,
            deletedAt: $data['deleted_at'] ?? null,
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null,
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

    public function updateDetails(
        string $name,
        ?string $description,
        ?string $primaryCode,
        ?string $secondaryCode,
        ?string $scType,
        int $unitId
    ): void {
        $this->name = $name;
        $this->description = $description;
        $this->primaryCode = $primaryCode;
        $this->secondaryCode = $secondaryCode;
        $this->scType = $scType;
        $this->unitId = $unitId;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }
}
