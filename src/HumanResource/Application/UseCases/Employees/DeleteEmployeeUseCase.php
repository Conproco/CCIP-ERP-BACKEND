<?php

namespace Src\HumanResource\Application\UseCases\Employees;

use Src\HumanResource\Domain\Ports\Repositories\Employees\EmployeeRepositoryInterface;

/**
 * Caso de uso para eliminar un empleado.
 */
class DeleteEmployeeUseCase
{
    public function __construct(
        private EmployeeRepositoryInterface $employeeRepository
    ) {
    }

    public function execute(int $employeeId): void
    {
        $this->employeeRepository->delete($employeeId);
    }
}
