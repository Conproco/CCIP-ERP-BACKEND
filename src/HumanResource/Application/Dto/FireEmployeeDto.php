<?php

namespace Src\HumanResource\Application\Dto;

use Illuminate\Http\UploadedFile;

/**
 * DTO para despedir un empleado.
 */
final class FireEmployeeDto
{
    public function __construct(
        public readonly int $employeeId,
        public readonly string $firedDate,
        public readonly int $daysTaken,
        public readonly string $state = 'Fired',
        public readonly ?UploadedFile $dischargeDocument = null,
    ) {
    }
}
