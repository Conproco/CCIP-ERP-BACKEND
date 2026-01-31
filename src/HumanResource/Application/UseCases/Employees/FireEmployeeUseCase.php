<?php

namespace Src\HumanResource\Application\UseCases\Employees;

use Illuminate\Support\Facades\DB;
use Src\HumanResource\Application\Dto\FireEmployeeDto;
use Src\HumanResource\Domain\Ports\Repositories\Employees\EmployeeRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\ContractRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\PayrollDetailRepositoryInterface;
use Src\HumanResource\Domain\Exceptions\EmployeeNotFoundException;
use Src\HumanResource\Domain\Exceptions\ContractNotFoundException;
use Src\Shared\Application\Interfaces\FileStorageInterface;

/**
 * Caso de uso para despedir un empleado.
 */
class FireEmployeeUseCase
{
    private const DISCHARGE_DOCUMENT_PATH = 'documents/discharge_document/';

    public function __construct(
        private EmployeeRepositoryInterface $employeeRepository,
        private ContractRepositoryInterface $contractRepository,
        private PayrollDetailRepositoryInterface $payrollDetailRepository,
        private FileStorageInterface $fileStorage
    ) {
    }

    public function execute(FireEmployeeDto $dto): void
    {
        DB::transaction(function () use ($dto) {
            $employee = $this->employeeRepository->find($dto->employeeId);

            if (!$employee) {
                throw new EmployeeNotFoundException($dto->employeeId);
            }

            $dischargeDocumentFilename = null;
            if ($dto->dischargeDocument) {
                $dischargeDocumentFilename = $this->fileStorage->generateFilename(
                    'documento_de_baja',
                    $employee->getName() . '_' . $employee->getLastname(),
                    $dto->dischargeDocument->getClientOriginalExtension()
                );
            }

            $data = [
                'state' => $dto->state,
                'fired_date' => $dto->firedDate,
                'days_taken' => $dto->daysTaken,
            ];

            if ($dischargeDocumentFilename) {
                $data['discharge_document'] = $dischargeDocumentFilename;
            }

            $updated = $this->contractRepository->update($dto->employeeId, $data);

            if (!$updated) {
                throw new ContractNotFoundException($dto->employeeId);
            }

            // Guardar archivo después de la actualización exitosa
            if ($dto->dischargeDocument && $dischargeDocumentFilename) {
                $this->fileStorage->store(
                    $dto->dischargeDocument,
                    self::DISCHARGE_DOCUMENT_PATH,
                    $dischargeDocumentFilename
                );
            }

            // Actualizar PayrollDetail si existe
            $payroll = $this->payrollDetailRepository->findLatestByEmployeeId($dto->employeeId);

            if ($payroll) {
                $this->payrollDetailRepository->updateFiredData(
                    $payroll->id,
                    $dto->firedDate,
                    $dto->daysTaken
                );
            }
        });
    }
}

