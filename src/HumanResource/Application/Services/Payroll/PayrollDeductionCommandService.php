<?php

namespace Src\HumanResource\Application\Services\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDeductionRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDeductionInstallmentRepositoryInterface;
use Src\HumanResource\Application\Dto\Payroll\StorePayrollDeductionDto;
use Src\HumanResource\Application\Dto\Payroll\UpdatePayrollDeductionDto;
use Src\Shared\Application\Interfaces\FileStorageInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayrollDeductionCommandService
{
    private const DOCUMENTS_PATH = 'documents/discount/';

    public function __construct(
        private PayrollDeductionRepositoryInterface $deductionRepository,
        private PayrollDeductionInstallmentRepositoryInterface $installmentRepository,
        private FileStorageInterface $fileStorage
    ) {
    }

    /**
     * Store a new payroll deduction with files and installments (transactional)
     */
    public function store(StorePayrollDeductionDto $dto): object
    {
        return DB::transaction(function () use ($dto) {
            // Generate filenames
            $depositVoucherFilename = $this->fileStorage->generateFilename(
                'DescuentosPlanilla',
                'deposit_voucher',
                $dto->depositVoucher->getClientOriginalExtension()
            );

            $authorizationFileFilename = $this->fileStorage->generateFilename(
                'DescuentosPlanilla',
                'authorization_file',
                $dto->authorizationFile->getClientOriginalExtension()
            );

            // Prepare data for creation
            $data = $dto->toArray();
            $data['deposit_voucher'] = $depositVoucherFilename;
            $data['authorization_file'] = $authorizationFileFilename;

            // Create deduction
            $deduction = $this->deductionRepository->create($data);

            // Store files
            $this->fileStorage->store($dto->depositVoucher, self::DOCUMENTS_PATH, $depositVoucherFilename);
            $this->fileStorage->store($dto->authorizationFile, self::DOCUMENTS_PATH, $authorizationFileFilename);

            // Create installments
            $this->createInstallments($dto, $deduction->id);

            return $deduction;
        });
    }

    /**
     * Create installments for a deduction
     */
    private function createInstallments(StorePayrollDeductionDto $dto, int $deductionId): void
    {
        $amount = $dto->amount;
        $installmentsQuantity = $dto->installmentsQuantity;
        $startDate = Carbon::parse($dto->startDate);

        $installmentAmount = round($amount / $installmentsQuantity, 2);
        $totalCreated = 0.0;

        for ($i = 0; $i < $installmentsQuantity; $i++) {
            $currentAmount = $installmentAmount;

            // Last installment gets remaining amount to avoid rounding issues
            if ($i === $installmentsQuantity - 1) {
                $currentAmount = $amount - $totalCreated;
            }

            $this->installmentRepository->create([
                'approximate_payment_date' => $startDate->copy()->addMonths($i)->endOfMonth(),
                'amount' => $currentAmount,
                'payment_status' => 'Pendiente',
                'operation_date' => $dto->operationDate,
                'operation_number' => $dto->operationNumber,
                'employee_id' => $dto->employeeId,
                'payroll_deduction_id' => $deductionId,
                'payroll_detail_monetary_discount_id' => null,
            ]);

            $totalCreated += $currentAmount;
        }
    }

    /**
     * Update a payroll deduction
     */
    public function update(UpdatePayrollDeductionDto $dto): object
    {
        return $this->deductionRepository->update($dto->id, $dto->toArray());
    }

    /**
     * Delete a payroll deduction with its files and installments
     */
    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            // Get deduction to delete files
            $deduction = $this->deductionRepository->find($id);

            if (!$deduction) {
                return false;
            }

            // Delete files
            if ($deduction->deposit_voucher) {
                $this->fileStorage->delete(self::DOCUMENTS_PATH . $deduction->deposit_voucher);
            }
            if ($deduction->authorization_file) {
                $this->fileStorage->delete(self::DOCUMENTS_PATH . $deduction->authorization_file);
            }

            // Delete installments
            $this->installmentRepository->deleteByDeductionId($id);

            // Delete deduction
            return $this->deductionRepository->delete($id);
        });
    }
}
