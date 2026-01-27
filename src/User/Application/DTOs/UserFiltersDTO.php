<?php

namespace Src\User\Application\DTOs;

final class UserFiltersDTO
{
    public function __construct(
        public readonly ?string $searchQuery = null,
        public readonly ?array $platforms = null,
        public readonly ?int $roleId = null,
        public readonly ?int $areaId = null,
        public readonly ?bool $hasEmployee = null,
        public readonly ?bool $includeTrashed = false, 
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            searchQuery: $data['searchQuery'] ?? $data['query'] ?? $data['search'] ?? null,
            platforms: $data['platforms'] ?? null,
            roleId: $data['role_id'] ?? null,
            areaId: $data['area_id'] ?? null,
            hasEmployee: $data['has_employee'] ?? null,
            includeTrashed: $data['include_trashed'] ?? false,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'searchQuery' => $this->searchQuery,
            'platforms' => $this->platforms,
            'role_id' => $this->roleId,
            'area_id' => $this->areaId,
            'has_employee' => $this->hasEmployee,
            'include_trashed' => $this->includeTrashed,
        ], fn($value) => $value !== null);
    }
}
