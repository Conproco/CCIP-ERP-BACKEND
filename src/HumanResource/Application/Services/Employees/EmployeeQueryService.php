<?php

namespace Src\HumanResource\Application\Services\Employees;

use Src\HumanResource\Domain\Ports\Repositories\Employees\EmployeeRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\CostLineRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\EducationRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\ContractRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\DocumentSectionRepositoryInterface;
use Src\HumanResource\Application\Dto\EmployeeListResponseDto;
use Src\HumanResource\Application\Dto\EmployeeCreateResponse;
use Src\HumanResource\Application\Dto\EmployeeDetailsDto;
use Src\HumanResource\Application\Normalizer\EmployeeCreateNormalizer;
use Src\Shared\Application\Interfaces\FileStorageInterface;
use Carbon\Carbon;

class EmployeeQueryService
{
    private const PROFILE_IMAGE_PATH = 'image/profile/';
    private const CURRICULUM_PATH = 'documents/curriculum_vitae/';
    private const DISCHARGE_DOCUMENT_PATH = 'documents/discharge_document/';
    private const DEFAULT_IMAGE_PATH = 'image/projectimage/';

    public array $pensionList = [
        'Habitat',
        'Integra',
        'Prima',
        'Profuturo',
        'ONP'
    ];

    public function __construct(
        private EmployeeRepositoryInterface $employeeRepository,
        private CostLineRepositoryInterface $costLineRepository,
        private EducationRepositoryInterface $educationRepository,
        private ContractRepositoryInterface $contractRepository,
        private DocumentSectionRepositoryInterface $documentSectionRepository,
        private iterable $normalizers,
        private ?EmployeeCreateNormalizer $createNormalizer = null,
        private ?FileStorageInterface $fileStorage = null
    ) {
    }

    public function getAllActive(bool $paginate = true, int $perPage = 15): EmployeeListResponseDto
    {
        $employeesPaginated = $this->employeeRepository->getEmployeesByState('Active', $paginate, $perPage);
        $costLines = $this->costLineRepository->getAll();

        foreach ($this->normalizers as $normalizer) {
            if ($normalizer instanceof \Src\HumanResource\Application\Normalizer\EmployeeListNormalizer) {
                return $normalizer->normalize($employeesPaginated, $costLines);
            }
        }

        $employees = $employeesPaginated->getCollection()->all();
        $costLinesArr = [];
        foreach ($costLines as $costLine) {
            $costLinesArr[] = [
                'id' => $costLine->id,
                'name' => $costLine->name
            ];
        }
        $pagination = [
            'current_page' => $employeesPaginated->currentPage(),
            'last_page' => $employeesPaginated->lastPage(),
            'per_page' => $employeesPaginated->perPage(),
            'total' => $employeesPaginated->total(),
            'from' => $employeesPaginated->firstItem(),
            'to' => $employeesPaginated->lastItem(),
        ];
        return new EmployeeListResponseDto($employees, $costLinesArr, $pagination);
    }

    public function searchEmployees(?string $state, ?string $search, ?array $costLine, bool $paginate = true, int $perPage = 15): object
    {
        $employees = $this->employeeRepository->search($state, $search, $costLine, $paginate, $perPage);
        $costLines = $this->costLineRepository->getAll();

        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supports($employees)) {
                return $normalizer->normalize($employees, $costLines);
            }
            if ($normalizer->supports($employees)) {
                $employees = $normalizer->normalize($employees);
            }
        }

        return $employees;
    }

    public function getCreateFormData(): array
    {
        $costLines = $this->costLineRepository->getAll();
        $sections = $this->documentSectionRepository->getAllVisibleWithSubdivisions();

        $dto = new EmployeeCreateResponse(
            $this->pensionList,
            $costLines,
            $sections
        );

        if ($this->createNormalizer) {
            return $this->createNormalizer->normalize($dto);
        }

        return $dto->toArray();
    }

    /**
     * Get employee data for edit form
     */
    public function getEmployeeForEdit(int $id): array
    {
        $employee = $this->employeeRepository->findWithRelations($id);
        $costLines = $this->costLineRepository->getAll();

        return [
            'employee' => $employee,
            'pensions' => $this->pensionList,
            'costLines' => $costLines,
        ];
    }

    /**
     * Get employee details with all relations
     */
    public function getEmployeeDetails(int $id): EmployeeDetailsDto
    {
        $employee = $this->employeeRepository->findWithRelations($id);

        return new EmployeeDetailsDto(
            id: $employee->id,
            name: $employee->name,
            lastname: $employee->lastname,
            gender: $employee->gender,
            stateCivil: $employee->state_civil,
            birthdate: $employee->birthdate,
            dni: $employee->dni,
            email: $employee->email,
            emailCompany: $employee->email_company,
            phone1: $employee->phone1,
            croppedImage: $employee->cropped_image,
            contract: $employee->contract?->toArray(),
            education: $employee->education?->toArray(),
            address: $employee->address?->toArray(),
            emergencyContacts: $employee->emergency?->toArray() ?? [],
            familyDependents: $employee->family?->toArray() ?? [],
            health: $employee->health?->toArray(),
        );
    }

    /**
     * Get employee profile image
     */
    public function getProfileImage(int $employeeId)
    {
        $employee = $this->employeeRepository->find($employeeId);
        $filename = $employee?->getCroppedImage();

        if (!$filename && $this->fileStorage) {
            return $this->fileStorage->get(self::DEFAULT_IMAGE_PATH . 'DefaultUser.png');
        }

        if ($this->fileStorage && $filename) {
            return $this->fileStorage->get(self::PROFILE_IMAGE_PATH . $filename);
        }

        return null;
    }

    /**
     * Download employee CV
     */
    public function downloadCurriculum(int $educationId)
    {
        $education = $this->educationRepository->find($educationId);

        if (!$education || !$education->getCurriculumVitae() || !$this->fileStorage) {
            return null;
        }

        return $this->fileStorage->download(
            self::CURRICULUM_PATH . $education->getCurriculumVitae(),
            $education->getCurriculumVitae()
        );
    }

    /**
     * Get discharge document
     */
    public function getDischargeDocument(int $contractId)
    {
        $contract = $this->contractRepository->find($contractId);

        if (!$contract || !$contract->getDischargeDocument() || !$this->fileStorage) {
            return null;
        }

        return $this->fileStorage->get(self::DISCHARGE_DOCUMENT_PATH . $contract->getDischargeDocument());
    }

    /**
     * Get employees with upcoming birthdays (next 3 days)
     */
    public function getUpcomingBirthdays(): array
    {
        $now = Carbon::now();
        $endDate = $now->copy()->addDays(3);

        $employees = $this->employeeRepository->getBirthdaysInRange($now, $endDate);

        return $employees->map(function ($employee) {
            return [
                'id' => $employee->id ?? $employee->getId(),
                'name' => $employee->name ?? $employee->getName(),
                'lastname' => $employee->lastname ?? $employee->getLastname(),
                'birthdate' => $employee->birthdate ?? $employee->getBirthdate(),
            ];
        })->values()->toArray();
    }

    private function applyNormalizers($data, bool $isPaginated)
    {
        if ($isPaginated) {
            $items = $data->getCollection();
            foreach ($this->normalizers as $normalizer) {
                if ($normalizer->supports($items)) {
                    $items = $normalizer->normalize($items);
                }
            }
            $data->setCollection($items);
            return $data;
        }
        return $data;
    }

    public function getCostLines(): array
    {
        $costLines = $this->costLineRepository->getAll();
        $costLinesArr = [];
        foreach ($costLines as $costLine) {
            $costLinesArr[] = [
                'id' => $costLine->id,
                'name' => $costLine->name
            ];
        }
        return $costLinesArr;
    }
    //Metodo para obtener constantes de empleados activos para payroll
    public function getActiveEmployeesConstant()
    {
        return $this->employeeRepository->getActiveEmployeesConstant();
    }
}


