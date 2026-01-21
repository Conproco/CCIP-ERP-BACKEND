<?php

namespace Src\Units\Application\Services;

use Src\Units\Domain\Exceptions\UnitNotFoundException;
use Src\Units\Domain\Repositories\UnitRepository;
use Src\Product\Domain\Exceptions\ProductNotFoundException;
use Src\Product\Domain\Repositories\ProductRepository;
use Src\Units\Application\DTOs\UnitDTO;

class UnitService
{
    public function __construct(
        private readonly UnitRepository $unitRepository,
        private readonly ProductRepository $productRepository
    ) {}

    public function execute(): array
    {
        return array_map(
            fn ($unit) => UnitDTO::fromEntity($unit),
            $this->unitRepository->all()
        );
    }

    public function validateUnitForProduct(int $productId): int
    {
        $product = $this->productRepository->find($productId);
        
        if (!$product) {
            throw new ProductNotFoundException($productId);
        }

        $unitId = $product->unitId;
        
        if (!$unitId) {
            throw new UnitNotFoundException(0, $productId);
        }

        $unit = $this->unitRepository->find($unitId);
        
        if (!$unit) {
            throw new UnitNotFoundException($unitId, $productId);
        }

        return $unit->id;
    }
}
