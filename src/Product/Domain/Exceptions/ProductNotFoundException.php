<?php

namespace Src\Product\Domain\Exceptions;

use Exception;

class ProductNotFoundException extends Exception
{
    public function __construct(int $productId)
    {
        parent::__construct("Producto con ID {$productId} no encontrado");
    }
}
