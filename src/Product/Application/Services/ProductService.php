<?php

namespace Src\Product\Application\Services;

use Src\Product\Application\DTOs\CreateProductDTO;
use Src\Product\Application\DTOs\UpdateProductDTO;
use Src\Product\Application\DTOs\ProductFiltersDTO;
use Src\Product\Domain\Entities\ProductEntity;
use Src\Product\Domain\Exceptions\ProductNotFoundException;
use Src\Product\Domain\Repositories\ProductRepository;
use Src\Product\Domain\Rules\ProductRules;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductRules $productRules
    ) {}

    public function create(CreateProductDTO $dto): ProductEntity
    {
        $product = ProductEntity::fromArray($dto->toArray());
        
        $this->productRules->validateProductForCreation($product);

        return $this->productRepository->save($product);
    }

    public function update(UpdateProductDTO $dto): ProductEntity
    {
        $product = $this->productRepository->find($dto->id);
        
        if (!$product) {
            throw new ProductNotFoundException($dto->id);
        }

        $updatedProduct = ProductEntity::fromArray($dto->toArray());
        
        $this->productRules->validateProductForUpdate($updatedProduct);

        return $this->productRepository->update($dto->id, $dto->toArray());
    }

    public function delete(int $id): bool
    {
        $product = $this->productRepository->find($id);
        
        if (!$product) {
            throw new ProductNotFoundException($id);
        }

        return $this->productRepository->delete($id);
    }

    public function restore(int $id): bool
    {
        $product = $this->productRepository->findWithTrashed($id);
        
        if (!$product) {
            throw new ProductNotFoundException($id);
        }

        return $this->productRepository->restore($id);
    }

    public function find(int $id): ProductEntity
    {
        $product = $this->productRepository->find($id);
        
        if (!$product) {
            throw new ProductNotFoundException($id);
        }

        return $product;
    }

    public function findWithTrashed(int $id): ProductEntity
    {
        $product = $this->productRepository->findWithTrashed($id);
        
        if (!$product) {
            throw new ProductNotFoundException($id);
        }

        return $product;
    }

    public function paginate(ProductFiltersDTO $filters, int $perPage = 15): mixed
    {
        return $this->productRepository->paginate($filters->toArray(), $perPage);
    }

    public function search(string $search, array $fields = [], bool $includeTrashed = false): array
    {
        return $this->productRepository->search($search, $fields, $includeTrashed);
    }

    public function searchFirst(int $limit = 5): array
    {
        return $this->productRepository->searchFirst($limit);
    }

    /**
     * Valida que un producto exista y retorna su ID.
     * Reemplaza ProductServices::evaluateProduct()
     *
     * @param array $data Array con key 'id' del producto
     * @return int El ID del producto validado
     * @throws ProductNotFoundException Si el producto no existe
     */
    public function validateProductExists(array $data): int
    {
        $productId = $data['id'] ?? null;
        
        if (!$productId) {
            throw new ProductNotFoundException(0);
        }

        $product = $this->productRepository->find($productId);
        
        if (!$product) {
            throw new ProductNotFoundException($productId);
        }

        return $product->id;
    }
}

