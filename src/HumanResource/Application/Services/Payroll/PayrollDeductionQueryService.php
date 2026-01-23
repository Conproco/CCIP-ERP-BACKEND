<?php

namespace Src\HumanResource\Application\Services\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDeductionRepositoryInterface;
use Src\HumanResource\Application\Dto\Payroll\PayrollDeductionIndexDto;
use Src\Shared\Application\Interfaces\FileStorageInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PayrollDeductionQueryService
{
    private const DOCUMENTS_PATH = 'documents/discount/';

    private const REASONS = ['Prestamo', 'Adelanto'];

    public function __construct(
        private PayrollDeductionRepositoryInterface $repository,
        private FileStorageInterface $fileStorage
    ) {
    }

    /**
     * Get index data with reason options
     */
    public function getIndexData(): PayrollDeductionIndexDto
    {
        return new PayrollDeductionIndexDto(self::REASONS);
    }

    /**
     * Get paginated list of deductions with filters
     */
    public function getPayrollDeductions(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getAllPaginated($filters, $perPage);
    }

    /**
     * Get a specific file from a deduction
     */
    public function showFile(string $fileType, int $deductionId)
    {
        $allowedFiles = ['deposit_voucher', 'authorization_file'];

        if (!in_array($fileType, $allowedFiles)) {
            abort(400, 'Tipo de archivo no válido');
        }

        $deduction = $this->repository->find($deductionId, ['deposit_voucher', 'authorization_file']);

        if (!$deduction || !$deduction->$fileType) {
            abort(404, 'Archivo no encontrado');
        }

        return $this->fileStorage->get(self::DOCUMENTS_PATH . $deduction->$fileType);
    }
}
