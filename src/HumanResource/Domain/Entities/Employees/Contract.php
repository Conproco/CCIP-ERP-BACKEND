<?php

namespace Src\HumanResource\Domain\Entities\Employees;

class Contract
{
    public function __construct(
        private ?int $id,
        private int $employeeId,
        private int $costLineId,
        private string $typeContract,
        private float $basicSalary,
        private string $hireDate,
        private string $pensionType,
        private bool $stateTravelExpenses = false,
        private ?float $amountTravelExpenses = null,
        private ?string $nroCuenta = null,
        private bool $lifeLey = false,
        private bool $discountRemuneration = false,
        private bool $discountSctr = false,
        private string $state = 'Active',
        private int $daysTaken = 0,
        private ?string $firedDate = null,
        private ?string $personalSegment = null,
        private ?string $dischargeDocument = null,
        private ?string $cuspp = null,
    ) {}

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }

    public function getCostLineId(): int
    {
        return $this->costLineId;
    }

    public function getTypeContract(): string
    {
        return $this->typeContract;
    }

    public function getBasicSalary(): float
    {
        return $this->basicSalary;
    }

    public function getHireDate(): string
    {
        return $this->hireDate;
    }

    public function getPensionType(): string
    {
        return $this->pensionType;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getFiredDate(): ?string
    {
        return $this->firedDate;
    }

    public function isActive(): bool
    {
        return $this->state === 'Active';
    }

    public function isFired(): bool
    {
        return $this->state === 'Fired';
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function updateSalary(float $basicSalary): void
    {
        $this->basicSalary = $basicSalary;
    }

    public function fire(string $firedDate, int $daysTaken, ?string $dischargeDocument): void
    {
        $this->state = 'Fired';
        $this->firedDate = $firedDate;
        $this->daysTaken = $daysTaken;
        $this->dischargeDocument = $dischargeDocument;
    }

    public function reentry(string $reentryDate): void
    {
        $this->state = 'Active';
        $this->hireDate = $reentryDate;
        $this->firedDate = null;
        $this->daysTaken = 0;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'cost_line_id' => $this->costLineId,
            'type_contract' => $this->typeContract,
            'basic_salary' => $this->basicSalary,
            'hire_date' => $this->hireDate,
            'pension_type' => $this->pensionType,
            'state_travel_expenses' => $this->stateTravelExpenses,
            'amount_travel_expenses' => $this->amountTravelExpenses,
            'nro_cuenta' => $this->nroCuenta,
            'life_ley' => $this->lifeLey,
            'discount_remuneration' => $this->discountRemuneration,
            'discount_sctr' => $this->discountSctr,
            'state' => $this->state,
            'days_taken' => $this->daysTaken,
            'fired_date' => $this->firedDate,
            'personal_segment' => $this->personalSegment,
            'discharge_document' => $this->dischargeDocument,
            'cuspp' => $this->cuspp,
        ];
    }
}
