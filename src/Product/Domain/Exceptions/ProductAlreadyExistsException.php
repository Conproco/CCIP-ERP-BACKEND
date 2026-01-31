<?php

namespace Src\Product\Domain\Exceptions;

use Exception;

class ProductAlreadyExistsException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("Ya existe un producto con el nombre: {$name}");
    }
}
