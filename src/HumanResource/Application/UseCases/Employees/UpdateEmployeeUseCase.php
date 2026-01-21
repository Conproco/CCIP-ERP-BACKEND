<?php

namespace Src\HumanResource\Application\UseCases\Employees;

use Illuminate\Support\Facades\DB;
use Src\HumanResource\Application\Dto\UpdateEmployeeDto;
use Src\HumanResource\Domain\Entities\Employees\Employee;
use Src\HumanResource\Domain\Entities\Employees\Contract;
use Src\HumanResource\Domain\Entities\Employees\Education;
use Src\HumanResource\Domain\Entities\Employees\Address;
use Src\HumanResource\Domain\Entities\Employees\EmergencyContact;
use Src\HumanResource\Domain\Entities\Employees\FamilyDependent;
use Src\HumanResource\Domain\Entities\Employees\Health;
use Src\HumanResource\Domain\Ports\Repositories\Employees\EmployeeRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\ContractRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\EducationRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\AddressRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\EmergencyContactRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\FamilyDependentRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\HealthRepositoryInterface;
use Src\Shared\Application\Interfaces\FileStorageInterface;
use Src\Shared\Domain\ValueObjects\Dni;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Telefono;

/**
 * Caso de uso para actualizar un empleado existente con todas sus relaciones.
 */
class UpdateEmployeeUseCase
{
    private const PROFILE_IMAGE_PATH = 'image/profile/';
    private const CURRICULUM_PATH = 'documents/curriculum_vitae/';

    public function __construct(
        private EmployeeRepositoryInterface $employeeRepository,
        private ContractRepositoryInterface $contractRepository,
        private EducationRepositoryInterface $educationRepository,
        private AddressRepositoryInterface $addressRepository,
        private EmergencyContactRepositoryInterface $emergencyContactRepository,
        private FamilyDependentRepositoryInterface $familyDependentRepository,
        private HealthRepositoryInterface $healthRepository,
        private FileStorageInterface $fileStorage
    ) {
    }

    public function execute(UpdateEmployeeDto $dto): void
    {
        DB::transaction(function () use ($dto) {
            $this->updateEmployee($dto);
            $this->updateContract($dto);
            $this->updateEducation($dto);
            $this->updateAddress($dto);
            $this->updateEmergencyContacts($dto->emergencyContacts, $dto->employeeId);
            $this->updateFamilyDependents($dto->familyDependents, $dto->employeeId);
            $this->updateHealth($dto);
        });
    }

    private function updateEmployee(UpdateEmployeeDto $dto): void
    {
        $dniVo = new Dni($dto->dni);
        $emailVo = new Email($dto->email);
        $emailCompanyVo = $dto->emailCompany ? new Email($dto->emailCompany) : null;
        $phoneVo = new Telefono($dto->phone1);

        $existingEmployee = $this->employeeRepository->find($dto->employeeId);
        $croppedImageFilename = $existingEmployee?->getCroppedImage();

        if ($dto->croppedImage) {
            // Eliminar imagen anterior si existe
            if ($croppedImageFilename) {
                $this->fileStorage->delete(self::PROFILE_IMAGE_PATH . $croppedImageFilename);
            }
            $croppedImageFilename = $this->fileStorage->generateFilename(
                'profile',
                $dto->dni,
                $dto->croppedImage->getClientOriginalExtension()
            );
            $this->fileStorage->store($dto->croppedImage, self::PROFILE_IMAGE_PATH, $croppedImageFilename);
        }

        $employee = new Employee(
            id: $dto->employeeId,
            name: $dto->name,
            lastname: $dto->lastname,
            gender: $dto->gender,
            stateCivil: $dto->stateCivil,
            birthdate: $dto->birthdate,
            dni: $dniVo,
            email: $emailVo,
            emailCompany: $emailCompanyVo,
            phone1: $phoneVo,
            croppedImage: $croppedImageFilename,
        );

        $this->employeeRepository->update($employee);
    }

    private function updateContract(UpdateEmployeeDto $dto): void
    {
        $data = [
            'cost_line_id' => $dto->costLineId,
            'type_contract' => $dto->typeContract,
            'basic_salary' => $dto->basicSalary,
            'hire_date' => $dto->hireDate,
            'pension_type' => $dto->pensionType,
            'cuspp' => $dto->cuspp,
            'state_travel_expenses' => $dto->stateTravelExpenses,
            'amount_travel_expenses' => $dto->amountTravelExpenses,
            'nro_cuenta' => $dto->nroCuenta,
            'life_ley' => $dto->lifeLey,
            'discount_remuneration' => $dto->discountRemuneration,
            'discount_sctr' => $dto->discountSctr,
        ];

        $this->contractRepository->update($dto->employeeId, $data);
    }

    private function updateEducation(UpdateEmployeeDto $dto): void
    {
        $existingEducation = $this->educationRepository->findByEmployeeId($dto->employeeId);
        $curriculumFilename = $existingEducation?->getCurriculumVitae();

        if ($dto->curriculumVitae) {
            if ($curriculumFilename) {
                $this->fileStorage->delete(self::CURRICULUM_PATH . $curriculumFilename);
            }
            $curriculumFilename = $this->fileStorage->generateFilename(
                'cv',
                $dto->dni,
                $dto->curriculumVitae->getClientOriginalExtension()
            );
            $this->fileStorage->store($dto->curriculumVitae, self::CURRICULUM_PATH, $curriculumFilename);
        }

        $data = [
            'education_level' => $dto->educationLevel,
            'education_status' => $dto->educationStatus,
            'specialization' => $dto->specialization,
            'curriculum_vitae' => $curriculumFilename,
        ];

        $this->educationRepository->update($dto->employeeId, $data);
    }

    private function updateAddress(UpdateEmployeeDto $dto): void
    {
        $data = [
            'street_address' => $dto->streetAddress,
            'department' => $dto->department,
            'province' => $dto->province,
            'district' => $dto->district,
        ];

        $this->addressRepository->update($dto->employeeId, $data);
    }

    private function updateEmergencyContacts(array $contacts, int $employeeId): void
    {
        // Eliminar existentes y crear nuevos
        $this->emergencyContactRepository->deleteByEmployeeId($employeeId);

        if (empty($contacts)) {
            return;
        }

        $entities = [];
        foreach ($contacts as $contact) {
            $phoneVo = new Telefono($contact['emergency_phone']);
            $entities[] = new EmergencyContact(
                id: null,
                employeeId: $employeeId,
                emergencyName: $contact['emergency_name'],
                emergencyLastname: $contact['emergency_lastname'],
                emergencyRelations: $contact['emergency_relations'],
                emergencyPhone: $phoneVo,
            );
        }

        $this->emergencyContactRepository->saveMultiple($entities);
    }

    private function updateFamilyDependents(array $dependents, int $employeeId): void
    {
        $this->familyDependentRepository->deleteByEmployeeId($employeeId);

        if (empty($dependents)) {
            return;
        }

        $entities = [];
        foreach ($dependents as $dependent) {
            $dniVo = !empty($dependent['family_dni']) ? new Dni($dependent['family_dni']) : null;
            $entities[] = new FamilyDependent(
                id: null,
                employeeId: $employeeId,
                familyName: $dependent['family_name'],
                familyLastname: $dependent['family_lastname'],
                familyRelation: $dependent['family_relation'],
                familyEducation: $dependent['family_education'],
                familyDni: $dniVo,
            );
        }

        $this->familyDependentRepository->saveMultiple($entities);
    }

    private function updateHealth(UpdateEmployeeDto $dto): void
    {
        $data = [
            'blood_group' => $dto->bloodGroup,
            'weight' => $dto->weight,
            'height' => $dto->height,
            'shoe_size' => $dto->shoeSize,
            'shirt_size' => $dto->shirtSize,
            'pants_size' => $dto->pantsSize,
            'medical_condition' => $dto->medicalCondition,
            'allergies' => $dto->allergies,
            'operations' => $dto->operations,
            'accidents' => $dto->accidents,
            'vaccinations' => $dto->vaccinations,
        ];

        $this->healthRepository->update($dto->employeeId, $data);
    }
}
