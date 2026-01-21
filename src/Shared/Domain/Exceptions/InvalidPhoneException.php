<?php

namespace Src\Shared\Domain\Exceptions;

use Exception;

class InvalidPhoneException extends Exception
{
    public function __construct(string $message = "El teléfono proporcionado no es válido.", int $code = 422)
    {
        parent::__construct($message, $code);
    }
}
