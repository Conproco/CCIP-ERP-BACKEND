<?php

namespace Src\HumanResource\Application\Data\Payroll;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;

class PensionSystemData extends Data
{
    public function __construct(
        #[Required]
        public string $type,

        #[Required]
        public float $commission_flow,

        #[Required]
        public float $annual_commission_balance,

        #[Required]
        public float $insurance_premium,

        #[Required]
        public float $mandatory_contribution,
    ) {
    }
}
