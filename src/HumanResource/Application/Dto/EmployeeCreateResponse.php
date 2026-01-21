<?php

namespace Src\HumanResource\Application\Dto;

class EmployeeCreateResponse
{
    public function __construct(
        public array $pensions,
        public $costLines,
        public $sections
    ) {}

    public function toArray(): array
    {
        return [
            'pensions' => $this->pensions,
            'costLines' => $this->costLines,
            'sections' => $this->sections,
        ];
    }
}
