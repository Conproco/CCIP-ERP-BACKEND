<?php

namespace Src\HumanResource\Domain\Entities\Employees;

class DocumentSection
{
    public function __construct(
        private ?int $id = null,
        private ?string $name = null,
        private bool $isVisible = true,
        private array $subdivisions = [],
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getIsVisible(): bool
    {
        return $this->isVisible;
    }

    public function getSubdivisions(): array
    {
        return $this->subdivisions;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_visible' => $this->isVisible,
            'subdivisions' => $this->subdivisions,
        ];
    }
}
