<?php

namespace Src\Units\Domain\Exceptions;

use Exception;

class UnitNotFoundException extends Exception
{
    public function __construct(int $unitId, ?int $productId = null)
    {
        $message = $productId 
            ? "Unidad no encontrada para el producto con ID {$productId}"
            : "Unidad con ID {$unitId} no encontrada";
        parent::__construct($message);
    }
}
