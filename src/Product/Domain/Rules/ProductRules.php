<?php

namespace Src\Product\Domain\Rules;

use Src\Product\Domain\Entities\ProductEntity;
use Src\Product\Domain\Exceptions\ProductAlreadyExistsException;
use Src\Product\Domain\Repositories\ProductRepository;

class ProductRules
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {}

    public function validateProductForCreation(ProductEntity $product): void
    {
        $this->validateUniqueName($product->name);
    }

    public function validateProductForUpdate(ProductEntity $product): void
    {
        $this->validateUniqueNameForUpdate($product->name, $product->id);
    }

    private function validateUniqueName(string $name): void
    {
        $existingProduct = $this->productRepository->findByName($name);
        
        if ($existingProduct) {
            throw new ProductAlreadyExistsException($name);
        }
    }

    private function validateUniqueNameForUpdate(string $name, ?int $productId): void
    {
        $existingProduct = $this->productRepository->findByName($name);
        
        if ($existingProduct && $existingProduct->id !== $productId) {
            throw new ProductAlreadyExistsException($name);
        }
    }
}
