<?php

namespace Src\HumanResource\Application\Services\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDeductionInstallmentRepositoryInterface;
use Src\HumanResource\Application\Dto\Payroll\PrepaymentDto;
use Src\Shared\Application\Interfaces\FileStorageInterface;
use Illuminate\Support\Facades\DB;

class PayrollDeductionInstallmentCommandService
{
    private const DOCUMENTS_PATH = 'documents/discount/';

    public function __construct(
        private PayrollDeductionInstallmentRepositoryInterface $repository,
        private FileStorageInterface $fileStorage
    ) {
    }

    /**
     * Process prepayment for an installment
     */
    public function prepayment(PrepaymentDto $dto): object
    {
        return DB::transaction(function () use ($dto) {
            // Generate filename
            $depositVoucherFilename = $this->fileStorage->generateFilename(
                'CuotasDescuento',
                'VoucherPago',
                $dto->depositVoucher->getClientOriginalExtension()
            );

            // Update installment
            $installment = $this->repository->update($dto->installmentId, [
                'payment_status' => 'Pagado',
                'deposit_voucher' => $depositVoucherFilename
            ]);

            // Store file
            $this->fileStorage->store($dto->depositVoucher, self::DOCUMENTS_PATH, $depositVoucherFilename);

            return $installment;
        });
    }

    /**
     * Revert installments to "Pendiente" when payroll is deleted.
     * Called by the PayrollDeleted event listener.
     */
    public function revertByDiscountIds(array $discountIds): void
    {
        if (empty($discountIds)) {
            return;
        }

        $this->repository->revertByDiscountIds($discountIds);
    }
}
