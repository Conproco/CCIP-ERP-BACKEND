<?php

namespace Src\HumanResource\Application\Services\Employees;

use Src\HumanResource\Domain\Ports\Repositories\Employees\EmployeeRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\CostLineRepositoryInterface;
use Src\HumanResource\Application\Dto\EmployeeListResponseDto;

class EmployeeQueryService
{
    // Propiedad movida aquí
    public array $pensionList = [
        'Habitat', 'Integra', 'Prima', 'Profuturo', 'ONP'
    ];

    public function __construct(
        private EmployeeRepositoryInterface $employeeRepository,
        private CostLineRepositoryInterface $costLineRepository,
        private iterable $normalizers 
    ) {}

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

    public function getSearchData(?string $state, ?string $search, ?array $costLine): object
    {
        $employees = $this->employeeRepository->search($state, $search, $costLine);
        return $this->applyNormalizers($employees, false); // Asumiendo que search no pagina igual
    }

    public function getFormData(): array
    {
        // Lógica de DTOs y Normalizers para el formulario
        // ... (Tu código original de getCreateFormData)
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
        
        // ... lógica para no paginado
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