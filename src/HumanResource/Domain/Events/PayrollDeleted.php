<?php

declare(strict_types=1);

namespace Src\HumanResource\Domain\Events;

class PayrollDeleted
{
    public function __construct(
        public int $payrollId,
        public array $discountIds = []
    ) {
    }
}
