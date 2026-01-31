<?php

namespace Src\HumanResource\Application\Dto\Payroll;

class UpdatePayrollDeductionDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $reason,
        public readonly string $operationNumber,
        public readonly string $operationDate,
        public readonly string $observations,
        public readonly int $employeeId,
    ) {
    }

    public function toArray(): array
    {
        return [
            'reason' => $this->reason,
            'operation_number' => $this->operationNumber,
            'operation_date' => $this->operationDate,
            'observations' => $this->observations,
            'employee_id' => $this->employeeId,
        ];
    }
}
