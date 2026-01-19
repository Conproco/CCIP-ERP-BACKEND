<?php

namespace Src\User\Domain\ValueObjects;

use InvalidArgumentException;

final class Phone
{
    private string $phone;

    public function __construct(string $phone)
    {
        $this->validate($phone);
        $this->phone = trim($phone);
    }

    private function validate(string $phone): void
    {
        if (empty($phone)) {
            throw new InvalidArgumentException('El teléfono no puede estar vacío');
        }

        if (!preg_match('/^\d{9}$/', $phone)) {
            throw new InvalidArgumentException('Teléfono inválido: debe contener exactamente 9 dígitos');
        }
    }

    public function value(): string
    {
        return $this->phone;
    }

    public function __toString(): string
    {
        return $this->phone;
    }
}
