<?php

namespace Src\HumanResource\Application\Dto;

/**
 * DTO de respuesta con detalles completos del empleado.
 */
final class EmployeeDetailsDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $lastname,
        public readonly string $gender,
        public readonly string $stateCivil,
        public readonly string $birthdate,
        public readonly string $dni,
        public readonly string $email,
        public readonly ?string $emailCompany,
        public readonly string $phone1,
        public readonly ?string $croppedImage,
        public readonly ?array $contract,
        public readonly ?array $education,
        public readonly ?array $address,
        public readonly array $emergencyContacts,
        public readonly array $familyDependents,
        public readonly ?array $health,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'gender' => $this->gender,
            'state_civil' => $this->stateCivil,
            'birthdate' => $this->birthdate,
            'dni' => $this->dni,
            'email' => $this->email,
            'email_company' => $this->emailCompany,
            'phone1' => $this->phone1,
            'cropped_image' => $this->croppedImage,
            'contract' => $this->contract,
            'education' => $this->education,
            'address' => $this->address,
            'emergency_contacts' => $this->emergencyContacts,
            'family_dependents' => $this->familyDependents,
            'health' => $this->health,
        ];
    }
}
