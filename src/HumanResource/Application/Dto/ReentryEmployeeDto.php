<?php

namespace Src\HumanResource\Application\Dto;

/**
 * DTO para reingreso de empleado.
 */
final class ReentryEmployeeDto
{
    public function __construct(
        public readonly int $contractId,
        public readonly string $reentryDate,
    ) {
    }
}
