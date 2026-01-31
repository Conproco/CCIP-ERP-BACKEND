<?php

namespace Src\User\Domain\ValueObjects;

use InvalidArgumentException;

final class Email
{
    private string $email;

    public function __construct(string $email)
    {
        $this->validate($email);
        $this->email = strtolower(trim($email));
    }

    private function validate(string $email): void
    {
        if (empty($email)) {
            throw new InvalidArgumentException('El email no puede estar vacío');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email inválido: ' . $email);
        }
    }

    public function value(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
