<?php

namespace Src\Shared\Domain\Exceptions;

use Exception;

class InvalidMoneyException extends Exception
{
    public function __construct(string $message = "El valor de dinero no es válido.", int $code = 422)
    {
        parent::__construct($message, $code);
    }
}
