<?php

declare(strict_types=1);

namespace Src\HumanResource\Application\Dto;

/**
 * DTO para la respuesta de búsqueda de empleados
 */
class EmployeeSearchResponseDto
{
    public array $employees;

    public function __construct(array $employees)
    {
        $this->employees = $employees;
    }

    public function toArray(): array
    {
        return $this->employees;
    }
}
