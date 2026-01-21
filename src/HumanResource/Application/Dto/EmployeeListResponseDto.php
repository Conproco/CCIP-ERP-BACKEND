<?php
namespace Src\HumanResource\Application\Dto;

class EmployeeListResponseDto
{
    public array $employees;
    public array $costLines;
    public array $pagination;

    public function __construct(array $employees, array $costLines, array $pagination)
    {
        $this->employees = $employees;
        $this->costLines = $costLines;
        $this->pagination = $pagination;
    }

    public function toArray(): array
    {
        return [
            'employees' => $this->employees,
            'costLines' => $this->costLines,
            'pagination' => $this->pagination,
        ];
    }
}
