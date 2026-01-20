<?php

namespace Src\HumanResource\Domain\Exceptions;

use Exception;

/**
 * Excepción base para el dominio de HumanResource.
 * Todas las excepciones de dominio heredarán de esta.
 */
class HumanResourceDomainException extends Exception
{
    protected int $statusCode = 400;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
