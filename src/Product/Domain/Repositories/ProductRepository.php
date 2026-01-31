<?php

namespace Src\Product\Domain\Repositories;

use Src\Product\Domain\Entities\ProductEntity;

interface ProductRepository
{
    public function find(int $id): ?ProductEntity;
    
    public function findWithTrashed(int $id): ?ProductEntity;
    
    public function findByName(string $name): ?ProductEntity;
    
    public function all(array $filters = []): array;
    
    public function paginate(array $filters = [], int $perPage = 15): mixed;
    
    public function save(ProductEntity $product): ProductEntity;
    
    public function update(int $id, array $data): ProductEntity;
    
    public function delete(int $id): bool;
    
    public function restore(int $id): bool;
    
    public function search(string $search, array $fields = [], bool $includeTrashed = false): array;
    
    public function searchFirst(int $limit = 5): array;
}
