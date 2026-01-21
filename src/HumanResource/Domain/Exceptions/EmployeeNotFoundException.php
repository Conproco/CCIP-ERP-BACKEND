<?php

namespace Src\HumanResource\Domain\Exceptions;

/**
 * Excepción cuando no se encuentra un empleado.
 */
class EmployeeNotFoundException extends HumanResourceDomainException
{
    protected int $statusCode = 404;

    public function __construct(int $employeeId)
    {
        parent::__construct("Employee not found with ID: {$employeeId}");
    }
}
