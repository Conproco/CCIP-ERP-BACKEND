<?php

namespace Src\User\Domain\ValueObjects;

use InvalidArgumentException;

final class Dni
{
    private string $dni;

    public function __construct(string $dni)
    {
        $this->validate($dni);
        $this->dni = trim($dni);
    }

    private function validate(string $dni): void
    {
        if (empty($dni)) {
            throw new InvalidArgumentException('El DNI no puede estar vacío');
        }

        if (!preg_match('/^\d{8}$/', $dni)) {
            throw new InvalidArgumentException('DNI inválido: debe contener exactamente 8 dígitos');
        }
    }

    public function value(): string
    {
        return $this->dni;
    }

    public function __toString(): string
    {
        return $this->dni;
    }
}
