<?php

namespace Src\Product\Application\DTOs;

class ProductFiltersDTO
{
    public function __construct(
        public readonly ?string $searchQuery = null,
        public readonly ?string $state = 'active',
        public readonly ?int $unitId = null,
        public readonly ?string $sortBy = 'created_at',
        public readonly ?string $sortDirection = 'desc',
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            searchQuery: $data['searchQuery'] ?? null,
            state: $data['state'] ?? 'active',
            unitId: isset($data['unit_id']) ? (int)$data['unit_id'] : null,
            sortBy: $data['sortBy'] ?? 'created_at',
            sortDirection: $data['sortDirection'] ?? 'desc',
        );
    }

    public function toArray(): array
    {
        return [
            'searchQuery' => $this->searchQuery,
            'state' => $this->state,
            'unit_id' => $this->unitId,
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection,
        ];
    }
}
