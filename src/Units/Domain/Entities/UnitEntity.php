<?php

namespace Src\Units\Domain\Entities;

class UnitEntity
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? '',

        );
    }
}
