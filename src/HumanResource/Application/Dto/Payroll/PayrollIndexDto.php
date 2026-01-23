<?php

namespace Src\HumanResource\Application\Dto\Payroll;

class PayrollIndexDto
{
    public function __construct(
        public readonly object $payrolls,
        public readonly array $pagination
    ) {
    }

    public function toArray(): array
    {
        return [
            'payroll' => $this->payrolls,
            'pagination' => $this->pagination,
        ];
    }
}
