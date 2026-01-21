<?php

namespace Src\Shared\Domain\ValueObjects;

use Src\Shared\Domain\Exceptions\InvalidPhoneException;

class Telefono
{
    private string $value;

    public function __construct(string $value)
    {
        // Simple validation: only digits, length 7-15
        if (!preg_match('/^\d{7,15}$/', $value)) {
            throw new InvalidPhoneException();
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
