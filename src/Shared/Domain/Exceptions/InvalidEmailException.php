<?php

namespace Src\Shared\Domain\Exceptions;

use Exception;

class InvalidEmailException extends Exception
{
    public function __construct(string $message = "El email proporcionado no es válido.", int $code = 422)
    {
        parent::__construct($message, $code);
    }
}
