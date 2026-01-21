<?php

namespace Src\HumanResource\Application\Dto\ExternalEmployees;

class ExternalEmployeeIndexDto
{
    public function __construct(
        public readonly array $costLines
    ) {
    }

    public function toArray(): array
    {
        return [
            'costLines' => $this->costLines,
        ];
    }
}
