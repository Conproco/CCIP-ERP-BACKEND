<?php

namespace Src\HumanResource\Application\Dto\Payroll;

class GetAmountMassiveDto
{
    public function __construct(
        public readonly array $ids,
        public readonly string $type
    ) {
    }

    public function toArray(): array
    {
        return [
            'ids' => $this->ids,
            'type' => $this->type,
        ];
    }
}
