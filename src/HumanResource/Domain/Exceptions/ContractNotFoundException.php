<?php

namespace Src\HumanResource\Domain\Exceptions;

/**
 * Excepción cuando no se encuentra un contrato.
 */
class ContractNotFoundException extends HumanResourceDomainException
{
    protected int $statusCode = 404;

    public function __construct(int $employeeId)
    {
        parent::__construct("Contract not found for employee ID: {$employeeId}");
    }
}
