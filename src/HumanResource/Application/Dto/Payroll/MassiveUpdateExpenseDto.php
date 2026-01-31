<?php

declare(strict_types=1);

namespace Src\HumanResource\Application\Dto\Payroll;

final readonly class MassiveUpdateExpenseDto
{
    public function __construct(
        public array $ids,
        public string $operationDate,
        public string $operationNumber,
    ) {
    }

    public function toUpdateData(): array
    {
        return [
            'operation_date' => $this->operationDate,
            'operation_number' => $this->operationNumber,
        ];
    }
}
