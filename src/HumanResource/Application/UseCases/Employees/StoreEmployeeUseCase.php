<?php

namespace Src\HumanResource\Application\UseCases\Employees;

use Illuminate\Support\Facades\DB;
use Src\HumanResource\Application\Dto\StoreEmployeeDto;
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
 * Caso de uso para crear un empleado completo con todas sus relaciones.
 * Maneja la transacción, validaciones con Value Objects y persistencia.
 */
class StoreEmployeeUseCase
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

    /**
     * Ejecuta la creación del empleado completo.
     *
     * @param StoreEmployeeDto $dto
     * @return int El ID del empleado creado
     * @throws \Src\Shared\Domain\Exceptions\InvalidDniException
     * @throws \Src\Shared\Domain\Exceptions\InvalidEmailException
     * @throws \Src\Shared\Domain\Exceptions\InvalidPhoneException
     */
    public function execute(StoreEmployeeDto $dto): int
    {
        return DB::transaction(function () use ($dto) {
            // 1. Crear y guardar Employee con Value Objects
            $employeeId = $this->createEmployee($dto);

            // 2. Crear Contract
            $this->createContract($dto, $employeeId);

            // 3. Crear Education
            $this->createEducation($dto, $employeeId);

            // 4. Crear Address
            $this->createAddress($dto, $employeeId);

            // 5. Crear Emergency Contacts
            $this->createEmergencyContacts($dto->emergencyContacts, $employeeId);

            // 6. Crear Family Dependents
            $this->createFamilyDependents($dto->familyDependents, $employeeId);

            // 7. Crear Health
            $this->createHealth($dto, $employeeId);

            return $employeeId;
        });
    }

    private function createEmployee(StoreEmployeeDto $dto): int
    {
        // Validar con Value Objects (lanzarán excepciones si son inválidos)
        $dniVo = new Dni($dto->dni);
        $emailVo = new Email($dto->email);
        $emailCompanyVo = $dto->emailCompany ? new Email($dto->emailCompany) : null;
        $phoneVo = new Telefono($dto->phone1);

        // Manejar imagen de perfil
        $croppedImageFilename = null;
        if ($dto->croppedImage) {
            $croppedImageFilename = $this->fileStorage->generateFilename(
                'profile',
                $dto->dni,
                $dto->croppedImage->getClientOriginalExtension()
            );
            $this->fileStorage->store(
                $dto->croppedImage,
                self::PROFILE_IMAGE_PATH,
                $croppedImageFilename
            );
        }

        $employee = new Employee(
            id: null,
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

        $savedEmployee = $this->employeeRepository->save($employee);
        return $savedEmployee->getId();
    }

    private function createContract(StoreEmployeeDto $dto, int $employeeId): void
    {
        $contract = new Contract(
            id: null,
            employeeId: $employeeId,
            costLineId: $dto->costLineId,
            typeContract: $dto->typeContract,
            basicSalary: $dto->basicSalary,
            hireDate: $dto->hireDate,
            pensionType: $dto->pensionType,
            stateTravelExpenses: $dto->stateTravelExpenses,
            amountTravelExpenses: $dto->amountTravelExpenses,
            nroCuenta: $dto->nroCuenta,
            lifeLey: $dto->lifeLey,
            discountRemuneration: $dto->discountRemuneration,
            discountSctr: $dto->discountSctr,
            state: 'Active',
            daysTaken: 0,
            firedDate: null,
            personalSegment: null,
            dischargeDocument: null,
            cuspp: $dto->cuspp,
        );

        $this->contractRepository->save($contract);
    }

    private function createEducation(StoreEmployeeDto $dto, int $employeeId): void
    {
        // Manejar CV
        $curriculumFilename = null;
        if ($dto->curriculumVitae) {
            $curriculumFilename = $this->fileStorage->generateFilename(
                'cv',
                $dto->dni,
                $dto->curriculumVitae->getClientOriginalExtension()
            );
            $this->fileStorage->store(
                $dto->curriculumVitae,
                self::CURRICULUM_PATH,
                $curriculumFilename
            );
        }

        $education = new Education(
            id: null,
            employeeId: $employeeId,
            educationLevel: $dto->educationLevel,
            educationStatus: $dto->educationStatus,
            specialization: $dto->specialization,
            curriculumVitae: $curriculumFilename,
        );

        $this->educationRepository->save($education);
    }

    private function createAddress(StoreEmployeeDto $dto, int $employeeId): void
    {
        $address = new Address(
            id: null,
            employeeId: $employeeId,
            streetAddress: $dto->streetAddress,
            department: $dto->department,
            province: $dto->province,
            district: $dto->district,
        );

        $this->addressRepository->save($address);
    }

    private function createEmergencyContacts(array $contacts, int $employeeId): void
    {
        if (empty($contacts)) {
            return;
        }

        $emergencyEntities = [];
        foreach ($contacts as $contact) {
            // Validar teléfono con Value Object
            $phoneVo = new Telefono($contact['emergency_phone']);

            $emergencyEntities[] = new EmergencyContact(
                id: null,
                employeeId: $employeeId,
                emergencyName: $contact['emergency_name'],
                emergencyLastname: $contact['emergency_lastname'],
                emergencyRelations: $contact['emergency_relations'],
                emergencyPhone: $phoneVo,
            );
        }

        $this->emergencyContactRepository->saveMultiple($emergencyEntities);
    }

    private function createFamilyDependents(array $dependents, int $employeeId): void
    {
        if (empty($dependents)) {
            return;
        }

        $familyEntities = [];
        foreach ($dependents as $dependent) {
            // Validar DNI opcional con Value Object
            $dniVo = !empty($dependent['family_dni']) ? new Dni($dependent['family_dni']) : null;

            $familyEntities[] = new FamilyDependent(
                id: null,
                employeeId: $employeeId,
                familyName: $dependent['family_name'],
                familyLastname: $dependent['family_lastname'],
                familyRelation: $dependent['family_relation'],
                familyEducation: $dependent['family_education'],
                familyDni: $dniVo,
            );
        }

        $this->familyDependentRepository->saveMultiple($familyEntities);
    }

    private function createHealth(StoreEmployeeDto $dto, int $employeeId): void
    {
        $health = new Health(
            id: null,
            employeeId: $employeeId,
            medicalCondition: $dto->medicalCondition,
            allergies: $dto->allergies,
            operations: $dto->operations,
            accidents: $dto->accidents,
            vaccinations: $dto->vaccinations,
            bloodGroup: $dto->bloodGroup,
            weight: $dto->weight,
            height: $dto->height,
            shoeSize: $dto->shoeSize,
            shirtSize: $dto->shirtSize,
            pantsSize: $dto->pantsSize,
        );

        $this->healthRepository->save($health);
    }
}
