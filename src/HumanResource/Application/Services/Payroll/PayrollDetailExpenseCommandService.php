<?php

declare(strict_types=1);

namespace Src\HumanResource\Application\Services\Payroll;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Src\HumanResource\Application\Dto\Payroll\MassiveUpdateExpenseDto;
use Src\HumanResource\Application\Dto\Payroll\UpdatePayrollDetailExpenseDto;
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDetailExpenseRepositoryInterface;
use Src\Shared\Application\Interfaces\FileStorageInterface;

class PayrollDetailExpenseCommandService
{
    private const DOCUMENTS_PATH = 'documents/payrollexpenses/';

    public function __construct(
        private PayrollDetailExpenseRepositoryInterface $repository,
        private FileStorageInterface $fileStorage,
    ) {
    }

    /**
     * Update expense with optional file upload
     */
    public function update(UpdatePayrollDetailExpenseDto $dto): object
    {
        return DB::transaction(function () use ($dto) {
            $data = $dto->toArray();

            // Handle file upload if present
            if ($dto->photo) {
                $filename = $this->fileStorage->generateFilename(
                    'Planilla',
                    'expense',
                    $dto->photo->getClientOriginalExtension()
                );
                $data['photo'] = $filename;
            }

            // Update the expense
            $expense = $this->repository->update($dto->id, $data);

            // Store file after successful update
            if ($dto->photo && isset($filename)) {
                $this->fileStorage->store($dto->photo, self::DOCUMENTS_PATH, $filename);
            }

            $expense->append('real_state');
            return $expense;
        });
    }

    /**
     * Delete expense
     */
    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $expense = $this->repository->find($id);

            // Delete associated file if exists
            if ($expense?->photo) {
                $this->fileStorage->delete(self::DOCUMENTS_PATH . $expense->photo);
            }

            $this->repository->delete($id);
        });
    }

    /**
     * Massive update of expenses (operation date and number)
     */
    public function massiveUpdate(MassiveUpdateExpenseDto $dto): Collection
    {
        return DB::transaction(function () use ($dto) {
            $expenses = $this->repository->updateBatch($dto->ids, $dto->toUpdateData());
            $expenses->each->append('real_state');
            return $expenses;
        });
    }
}
