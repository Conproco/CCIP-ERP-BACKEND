<?php

namespace Src\HumanResource\Application\Dto;

/**
 * DTO de respuesta para la creación de empleado.
 */
final class StoreEmployeeResponseDto
{
    public function __construct(
        public readonly int $employeeId
    ) {
    }

    public function toArray(): array
    {
        return [
            'employee_id' => $this->employeeId,
        ];
    }
}
