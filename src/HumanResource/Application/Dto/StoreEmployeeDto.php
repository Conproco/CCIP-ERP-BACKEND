<?php

namespace Src\HumanResource\Application\Dto;

use Illuminate\Http\UploadedFile;

/**
 * DTO inmutable con todos los datos necesarios para crear un empleado completo.
 */
final class StoreEmployeeDto
{
    public function __construct(
        // Datos personales
        public readonly string $name,
        public readonly string $lastname,
        public readonly string $gender,
        public readonly string $stateCivil,
        public readonly string $birthdate,
        public readonly string $dni,
        public readonly string $email,
        public readonly ?string $emailCompany,
        public readonly string $phone1,
        public readonly ?UploadedFile $croppedImage,

        // Contrato
        public readonly int $costLineId,
        public readonly string $typeContract,
        public readonly float $basicSalary,
        public readonly string $hireDate,
        public readonly string $pensionType,
        public readonly ?string $cuspp,
        public readonly bool $stateTravelExpenses,
        public readonly ?float $amountTravelExpenses,
        public readonly ?string $nroCuenta,
        public readonly bool $lifeLey,
        public readonly bool $discountRemuneration,
        public readonly bool $discountSctr,

        // Educación
        public readonly string $educationLevel,
        public readonly string $educationStatus,
        public readonly string $specialization,
        public readonly ?UploadedFile $curriculumVitae,

        // Dirección
        public readonly string $streetAddress,
        public readonly string $department,
        public readonly string $province,
        public readonly string $district,

        // Contactos de emergencia (array de arrays)
        public readonly array $emergencyContacts,

        // Dependientes familiares (array de arrays)
        public readonly array $familyDependents,

        // Salud
        public readonly string $medicalCondition,
        public readonly string $allergies,
        public readonly string $operations,
        public readonly string $accidents,
        public readonly string $vaccinations,
        public readonly ?string $bloodGroup,
        public readonly ?float $weight,
        public readonly ?float $height,
        public readonly ?float $shoeSize,
        public readonly ?string $shirtSize,
        public readonly ?float $pantsSize,
    ) {
    }
}
