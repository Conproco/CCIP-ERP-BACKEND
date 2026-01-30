<?php

namespace Src\HumanResource\Application\Data\Payroll;

use Spatie\LaravelData\Data;
use Src\HumanResource\Domain\Enums\Payroll\PayrollPensionType;

class PayrollResponseData extends Data
{
    public function __construct(
        public PayrollData $payroll,
        public array $pension_types
    ) {
    }

    public static function create(PayrollData $payroll): self
    {
        return new self(
            payroll: $payroll,
            pension_types: PayrollPensionType::values()
        );
    }
}
