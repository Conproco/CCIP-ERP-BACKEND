<?php
namespace Src\Shared\Domain\ValueObjects;

use InvalidArgumentException; // O una excepción propia de Shared

final class Dni
{
    public function __construct(
        private string $value
    ) {
        $this->ensureIsValid($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    private function ensureIsValid(string $value): void
    {
        // Esta validación ahora sirve para Empleados, Clientes, Proveedores, etc.
        if (!preg_match('/^\d{8}$/', $value)) {
            throw new InvalidArgumentException("El DNI <$value> no es válido.");
        }
    }
}