<?php


namespace Src\HumanResource\Domain\Entities\Employees;

use Src\Shared\Domain\ValueObjects\Telefono;

class EmergencyContact
{
    public function __construct(
        private ?int $id,
        private int $employeeId,
        private string $emergencyName,
        private string $emergencyLastname,
        private string $emergencyRelations,
        private Telefono $emergencyPhone,
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

    public function getEmergencyName(): string
    {
        return $this->emergencyName;
    }

    public function getEmergencyLastname(): string
    {
        return $this->emergencyLastname;
    }

    public function getFullName(): string
    {
        return $this->emergencyName . ' ' . $this->emergencyLastname;
    }

    public function getEmergencyRelations(): string
    {
        return $this->emergencyRelations;
    }

    public function getEmergencyPhone(): Telefono
    {
        return $this->emergencyPhone;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function updateContact(
        string $emergencyName,
        string $emergencyLastname,
        string $emergencyRelations,
        Telefono $emergencyPhone
    ): void {
        $this->emergencyName = $emergencyName;
        $this->emergencyLastname = $emergencyLastname;
        $this->emergencyRelations = $emergencyRelations;
        $this->emergencyPhone = $emergencyPhone;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'emergency_name' => $this->emergencyName,
            'emergency_lastname' => $this->emergencyLastname,
            'emergency_relations' => $this->emergencyRelations,
            'emergency_phone' => $this->emergencyPhone->value(),
        ];
    }
}
