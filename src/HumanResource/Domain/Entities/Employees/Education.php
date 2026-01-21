<?php

namespace Src\HumanResource\Domain\Entities\Employees;

class Education
{
    public function __construct(
        private ?int $id,
        private int $employeeId,
        private string $educationLevel,
        private string $educationStatus,
        private string $specialization,
        private ?string $curriculumVitae = null,
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

    public function getEducationLevel(): string
    {
        return $this->educationLevel;
    }

    public function getEducationStatus(): string
    {
        return $this->educationStatus;
    }

    public function getSpecialization(): string
    {
        return $this->specialization;
    }

    public function getCurriculumVitae(): ?string
    {
        return $this->curriculumVitae;
    }

    public function isCompleted(): bool
    {
        return $this->educationStatus === 'Completo';
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function updateEducation(
        string $educationLevel,
        string $educationStatus,
        string $specialization
    ): void {
        $this->educationLevel = $educationLevel;
        $this->educationStatus = $educationStatus;
        $this->specialization = $specialization;
    }

    public function updateCurriculumVitae(?string $curriculumVitae): void
    {
        $this->curriculumVitae = $curriculumVitae;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'education_level' => $this->educationLevel,
            'education_status' => $this->educationStatus,
            'specialization' => $this->specialization,
            'curriculum_vitae' => $this->curriculumVitae,
        ];
    }
}
