<?php

namespace Src\Role\Domain\ValueObjects;

use InvalidArgumentException;

final class RoleName
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = trim($value);
    }

    private function validate(string $value): void
    {
        $trimmed = trim($value);
        
        if (empty($trimmed)) {
            throw new InvalidArgumentException('El nombre del rol no puede estar vacío');
        }

        if (strlen($trimmed) < 2) {
            throw new InvalidArgumentException('El nombre del rol debe tener al menos 2 caracteres');
        }

        if (strlen($trimmed) > 100) {
            throw new InvalidArgumentException('El nombre del rol no puede exceder los 100 caracteres');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
