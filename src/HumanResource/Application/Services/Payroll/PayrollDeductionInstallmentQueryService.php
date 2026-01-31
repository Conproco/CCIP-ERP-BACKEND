<?php

namespace Src\HumanResource\Application\Services\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDeductionInstallmentRepositoryInterface;
use Src\Shared\Application\Interfaces\FileStorageInterface;
use Illuminate\Database\Eloquent\Collection;

class PayrollDeductionInstallmentQueryService
{
    private const DOCUMENTS_PATH = 'documents/discount/';

    public function __construct(
        private PayrollDeductionInstallmentRepositoryInterface $repository,
        private FileStorageInterface $fileStorage
    ) {
    }

    /**
     * Get installments for a deduction with filters
     */
    public function getDeductionInstallments(int $deductionId, array $filters = []): Collection
    {
        return $this->repository->getByDeductionId($deductionId, $filters);
    }

    /**
     * Show file for an installment
     */
    public function showFile(int $installmentId)
    {
        $installment = $this->repository->find($installmentId, ['deposit_voucher']);

        if (!$installment || !$installment->deposit_voucher) {
            abort(404, 'Archivo no encontrado');
        }

        return $this->fileStorage->get(self::DOCUMENTS_PATH . $installment->deposit_voucher);
    }
}
