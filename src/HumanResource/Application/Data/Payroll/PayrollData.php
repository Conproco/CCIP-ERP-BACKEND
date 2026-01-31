<?php

namespace Src\HumanResource\Application\Data\Payroll;

use Spatie\LaravelData\Data;

class PayrollData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $month,
        public readonly bool $state,
        public readonly ?float $sctr_p,
        public readonly ?float $sctr_s,
        public readonly float $total_amount,
        public readonly ?string $created_at,
    ) {
    }

    /**
     * Create from stdClass (raw DB query result) or Eloquent model
     */
    public static function fromModel(object $payroll): self
    {
        return new self(
            id: (int) $payroll->id,
            month: $payroll->month,
            state: (bool) $payroll->state,
            sctr_p: $payroll->sctr_p ? (float) $payroll->sctr_p : null,
            sctr_s: $payroll->sctr_s ? (float) $payroll->sctr_s : null,
            total_amount: (float) ($payroll->total_amount ?? 0),
            created_at: $payroll->created_at,
        );
    }
}
