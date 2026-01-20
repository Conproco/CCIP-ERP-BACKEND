<?php

namespace Src\HumanResource\Domain\Entities\Employees;
use Src\Shared\Domain\ValueObjects\Dni;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Telefono;

class FamilyDependent
{
    public function __construct(
        private ?int $id,
        private int $employeeId,
        private string $familyName,
        private string $familyLastname,
        private string $familyRelation,
        private string $familyEducation,
        private ?Dni $familyDni = null,
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

    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    public function getFamilyLastname(): string
    {
        return $this->familyLastname;
    }

    public function getFullName(): string
    {
        return $this->familyName . ' ' . $this->familyLastname;
    }

    public function getFamilyRelation(): string
    {
        return $this->familyRelation;
    }

    public function getFamilyEducation(): string
    {
        return $this->familyEducation;
    }

    public function getFamilyDni(): ?Dni
    {
        return $this->familyDni;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function updateDependent(
        string $familyName,
        string $familyLastname,
        string $familyRelation,
        string $familyEducation,
        ?Dni $familyDni
    ): void {
        $this->familyName = $familyName;
        $this->familyLastname = $familyLastname;
        $this->familyRelation = $familyRelation;
        $this->familyEducation = $familyEducation;
        $this->familyDni = $familyDni;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'family_name' => $this->familyName,
            'family_lastname' => $this->familyLastname,
            'family_relation' => $this->familyRelation,
            'family_education' => $this->familyEducation,
            'family_dni' => $this->familyDni?->value(),
        ];
    }
}
