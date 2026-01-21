<?php

namespace Src\Product\Application\DTOs;

class CreateProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description,
        public readonly ?string $primaryCode,
        public readonly ?string $secondaryCode,
        public readonly ?string $scType,
        public readonly int $unitId,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            primaryCode: $data['primary_code'] ?? null,
            secondaryCode: $data['secondary_code'] ?? null,
            scType: $data['sc_type'] ?? null,
            unitId: (int)$data['unit_id'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'primary_code' => $this->primaryCode,
            'secondary_code' => $this->secondaryCode,
            'sc_type' => $this->scType,
            'unit_id' => $this->unitId,
        ];
    }
}
