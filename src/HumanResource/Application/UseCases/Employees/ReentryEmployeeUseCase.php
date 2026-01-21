<?php

namespace Src\HumanResource\Application\UseCases\Employees;

use Src\HumanResource\Application\Dto\ReentryEmployeeDto;
use Src\HumanResource\Domain\Ports\Repositories\Employees\ContractRepositoryInterface;

/**
 * Caso de uso para reingreso de empleado.
 */
class ReentryEmployeeUseCase
{
    public function __construct(
        private ContractRepositoryInterface $contractRepository
    ) {
    }

    public function execute(ReentryEmployeeDto $dto): void
    {
        $data = [
            'hire_date' => $dto->reentryDate,
            'fired_date' => null,
            'days_taken' => 0,
            'state' => 'Active',
        ];

        // Usamos contractId directamente ya que reentry trabaja con el ID del contrato
        $contract = $this->contractRepository->find($dto->contractId);
        if ($contract) {
            $this->contractRepository->update($contract->getEmployeeId(), $data);
        }
    }
}
