<?php
declare(strict_types=1);

namespace Src\HumanResource\Application\Normalizer;

use Src\HumanResource\Application\Dto\EmployeeListResponseDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EmployeeListNormalizer
{
    public function supports($data): bool
    {
        // Puedes ajustar la lógica según el tipo de datos que debe normalizar
        return true;
    }
    /**
     * Normalize employees and cost lines into a DTO response
     */
    public function normalize(
        LengthAwarePaginator|Collection $employees,
        Collection $costLines
    ): EmployeeListResponseDto {
        return new EmployeeListResponseDto(
            employees: $this->normalizeEmployees($employees),
            costLines: $this->normalizeCostLines($costLines),
            pagination: $employees instanceof LengthAwarePaginator 
                ? $this->normalizePagination($employees) 
                : []
        );
    }

    private function normalizeEmployees(LengthAwarePaginator|Collection $employees): array
    {
        $items = $employees instanceof LengthAwarePaginator 
            ? $employees->items() 
            : $employees->all();

        return array_map(fn($employee) => $this->normalizeEmployee($employee), $items);
    }

    private function normalizeEmployee(object $employee): array
    {
        return [
            'id' => $employee->id,
            'name' => $employee->name,
            'lastname' => $employee->lastname,
            'full_name' => "{$employee->name} {$employee->lastname}",
            'dni' => $employee->dni,
            'email' => $employee->email,
            'phone1' => $employee->phone1,
            'state' => $employee->state,
            'cropped_image' => $employee->cropped_image,
            'cost_line_id' => $employee->cost_line_id,
            'cost_line' => $employee->cost_line ? [
                'id' => $employee->cost_line->id,
                'name' => $employee->cost_line->name,
            ] : null,
        ];
    }

    private function normalizeCostLines(Collection $costLines): array
    {
        return $costLines->map(fn($costLine) => [
            'id' => $costLine->id,
            'name' => $costLine->name,
        ])->toArray();
    }

    private function normalizePagination(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ];
    }
}
