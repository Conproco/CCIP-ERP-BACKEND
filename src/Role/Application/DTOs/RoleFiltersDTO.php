<?php

namespace Src\Role\Application\DTOs;

final class RoleFiltersDTO
{
    public function __construct(
        public readonly ?string $searchQuery = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            searchQuery: $data['searchQuery'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'searchQuery' => $this->searchQuery,
        ], fn($value) => $value !== null);
    }
}
