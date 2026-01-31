<?php

declare(strict_types=1);

namespace Src\HumanResource\Domain\Exceptions;

use Exception;

class PayrollAlreadyClosedException extends HumanResourceDomainException
{
    protected int $statusCode = 422;

    public function __construct(int $id)
    {
        parent::__construct("No se puede eliminar la planilla ID {$id} porque ya está cerrada.");
    }
}
