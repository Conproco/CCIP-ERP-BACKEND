<?php

namespace Src\HumanResource\Domain\Entities\Payroll;

class Payroll
{
    public function __construct(
        private ?int $id = null,
        private ?string $month = null,
        private bool $state = false,
        private ?float $sctrP = null,
        private ?float $sctrS = null,
        private float $totalAmount = 0.0,
        private ?string $createdAt = null,
        private ?string $updatedAt = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function getState(): bool
    {
        return $this->state;
    }

    public function getSctrP(): ?float
    {
        return $this->sctrP;
    }

    public function getSctrS(): ?float
    {
        return $this->sctrS;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'month' => $this->month,
            'state' => $this->state,
            'sctr_p' => $this->sctrP,
            'sctr_s' => $this->sctrS,
            'total_amount' => $this->totalAmount,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
