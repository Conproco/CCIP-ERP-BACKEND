<?php

namespace Src\HumanResource\Domain\Entities\Employees;

class Health
{
    public function __construct(
        private ?int $id,
        private int $employeeId,
        private string $medicalCondition,
        private string $allergies,
        private string $operations,
        private string $accidents,
        private string $vaccinations,
        private ?string $bloodGroup = null,
        private ?float $weight = null,
        private ?float $height = null,
        private ?float $shoeSize = null,
        private ?string $shirtSize = null,
        private ?float $pantsSize = null,
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

    public function getBloodGroup(): ?string
    {
        return $this->bloodGroup;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function getMedicalCondition(): string
    {
        return $this->medicalCondition;
    }

    public function getAllergies(): string
    {
        return $this->allergies;
    }

    public function getOperations(): string
    {
        return $this->operations;
    }

    public function getAccidents(): string
    {
        return $this->accidents;
    }

    public function getVaccinations(): string
    {
        return $this->vaccinations;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function updateHealthInfo(
        string $medicalCondition,
        string $allergies,
        string $operations,
        string $accidents,
        string $vaccinations
    ): void {
        $this->medicalCondition = $medicalCondition;
        $this->allergies = $allergies;
        $this->operations = $operations;
        $this->accidents = $accidents;
        $this->vaccinations = $vaccinations;
    }

    public function updatePhysicalInfo(
        ?string $bloodGroup,
        ?float $weight,
        ?float $height,
        ?float $shoeSize,
        ?string $shirtSize,
        ?float $pantsSize
    ): void {
        $this->bloodGroup = $bloodGroup;
        $this->weight = $weight;
        $this->height = $height;
        $this->shoeSize = $shoeSize;
        $this->shirtSize = $shirtSize;
        $this->pantsSize = $pantsSize;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'blood_group' => $this->bloodGroup,
            'weight' => $this->weight,
            'height' => $this->height,
            'shoe_size' => $this->shoeSize,
            'shirt_size' => $this->shirtSize,
            'pants_size' => $this->pantsSize,
            'medical_condition' => $this->medicalCondition,
            'allergies' => $this->allergies,
            'operations' => $this->operations,
            'accidents' => $this->accidents,
            'vaccinations' => $this->vaccinations,
        ];
    }
}
