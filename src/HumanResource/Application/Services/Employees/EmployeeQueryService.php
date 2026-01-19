<?php

namespace Src\HumanResource\Application\Services\Employees;

use Src\HumanResource\Domain\Ports\Repositories\Employees\EmployeeRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\CostLineRepositoryInterface;
use Src\HumanResource\Application\Dto\EmployeeListResponseDto;
use Src\HumanResource\Application\Dto\EmployeeSearchResponseDto;
use Src\HumanResource\Application\Dto\EmployeeCreateResponse;
use Src\HumanResource\Application\Normalizer\EmployeeCreateNormalizer;
use App\Models\DocumentSection;

class EmployeeQueryService
{
    // Propiedad movida aquí
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
        private iterable $normalizers,
        private ?EmployeeCreateNormalizer $createNormalizer = null
    ) {
    }

    /**
     * getEmployees
     */
    public function getAllActive(bool $paginate = true, int $perPage = 15): EmployeeListResponseDto
    {
        $employeesPaginated = $this->employeeRepository->getEmployeesByState('Active', $paginate, $perPage);
        $costLines = $this->costLineRepository->getAll();

        // Si tienes EmployeeListNormalizer, úsalo y retorna directamente el resultado
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer instanceof \Src\HumanResource\Application\Normalizer\EmployeeListNormalizer) {
                return $normalizer->normalize($employeesPaginated, $costLines);
            }
        }

        // Si no hay normalizador, usa el DTO manualmente
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
        $employees = $this->employeeRepository->search($state, $search, $costLine);
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

    /**
     * Get data for create employee form
     * Returns: pensions, costLines, sections
     */
    public function getCreateFormData(): array
    {
        $costLines = $this->costLineRepository->getAll();
        $sections = $this->getDocumentSections();

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
     * Get visible document sections with subdivisions
     */
    private function getDocumentSections(): object
    {
        return DocumentSection::with([
            'subdivisions' => function ($subq) {
                $subq->where('is_visible', true);
            }
        ])
            ->where('is_visible', true)
            ->get();
    }

    // Helper para no repetir código de normalización
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
}
