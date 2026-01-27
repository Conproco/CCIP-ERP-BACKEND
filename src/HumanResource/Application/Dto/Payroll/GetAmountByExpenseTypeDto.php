<?php

namespace Src\HumanResource\Application\Dto\Payroll;

class GetAmountByExpenseTypeDto
{
    public function __construct(
        public readonly int $payrollDetailId,
        public readonly string $type
    ) {
    }

    public function toArray(): array
    {
        return [
            'payroll_detail_id' => $this->payrollDetailId,
            'type' => $this->type,
        ];
    }
}
