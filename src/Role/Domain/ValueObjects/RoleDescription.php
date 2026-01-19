<?php

namespace Src\Role\Domain\ValueObjects;

use InvalidArgumentException;

final class RoleDescription
{
    private ?string $value;

    public function __construct(?string $value = null)
    {
        $this->validate($value);
        $this->value = $value ? trim($value) : null;
    }

    private function validate(?string $value): void
    {
        if ($value !== null && strlen(trim($value)) > 500) {
            throw new InvalidArgumentException('La descripción del rol no puede exceder los 500 caracteres');
        }
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }
}
