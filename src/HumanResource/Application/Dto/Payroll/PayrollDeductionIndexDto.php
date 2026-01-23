<?php

namespace Src\HumanResource\Application\Dto\Payroll;

class PayrollDeductionIndexDto
{
    public function __construct(
        public readonly array $reasons
    ) {
    }

    public function toArray(): array
    {
        return [
            'reason' => $this->reasons,
        ];
    }
}
