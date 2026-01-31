<?php

namespace Src\HumanResource\Application\Data\Payroll;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\DataCollectionOf;

class StorePayrollData extends Data
{
    public function __construct(
        #[Required]
        public string $month,

        #[Required]
        public bool $state,

            /** @var PensionSystemData[] */
        #[Required]
        #[DataCollectionOf(PensionSystemData::class)]
        public array $pension_system,
    ) {
    }
}
