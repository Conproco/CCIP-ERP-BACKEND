<?php

namespace Src\HumanResource\Application\Dto\Payroll;

use Illuminate\Http\UploadedFile;

class StorePayrollDeductionDto
{
    public function __construct(
        public readonly string $reason,
        public readonly UploadedFile $depositVoucher,
        public readonly string $operationNumber,
        public readonly string $operationDate,
        public readonly UploadedFile $authorizationFile,
        public readonly string $observations,
        public readonly int $employeeId,
        public readonly int $installmentsQuantity,
        public readonly float $amount,
        public readonly string $startDate,
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
