<?php

declare(strict_types=1);

namespace Src\HumanResource\Application\Services\Payroll;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Src\HumanResource\Domain\Enums\Payroll\PayrollDocType;
use Src\HumanResource\Domain\Enums\Payroll\PayrollExpenseStateType;
use Src\HumanResource\Domain\Enums\Payroll\PayrollExpenseType;
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDetailExpenseRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDetailMonetaryIncomeRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDetailTaxAndContributionRepositoryInterface;
use Src\Shared\Application\Interfaces\FileStorageInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PayrollDetailExpenseQueryService
{
    private const DOCUMENTS_PATH = 'documents/payrollexpenses/';

    public function __construct(
        private PayrollDetailExpenseRepositoryInterface $repository,
        private PayrollRepositoryInterface $payrollRepository,
        private PayrollDetailMonetaryIncomeRepositoryInterface $monetaryIncomeRepository,
        private PayrollDetailTaxAndContributionRepositoryInterface $taxAndContributionRepository,
        private FileStorageInterface $fileStorage,
    ) {
    }

    /**
     * Get index data for API response
     */
    public function getIndexData(int $payrollId): array
    {
        $payroll = $this->payrollRepository->findOrFail($payrollId);

        return [
            'expenseTypes' => PayrollExpenseType::values(),
            'docTypes' => PayrollDocType::values(),
            'stateTypes' => PayrollExpenseStateType::values(),
            'payroll' => $payroll->toArray(),
        ];
    }

    /**
     * Get paginated expenses by payroll
     */
    public function getByPayrollId(int $payrollId): LengthAwarePaginator
    {
        $data = $this->repository->getByPayrollId($payrollId);
        /** @var \Illuminate\Pagination\LengthAwarePaginator $data */
        $data->getCollection()->each->append('real_state');
        return $data;
    }

    /**
     * Get expenses by payroll detail
     */
    public function getByPayrollDetailId(int $payrollDetailId): Collection
    {
        return $this->repository->getByPayrollDetailId($payrollDetailId);
    }

    /**
     * Search/filter expenses
     */
    public function search(int $payrollId, array $filters): Collection
    {
        return $this->repository->filter($payrollId, $filters);
    }

    /**
     * Get file for expense
     */
    public function showFile(int $expenseId): BinaryFileResponse
    {
        $expense = $this->repository->find($expenseId);

        if (!$expense || !$expense->photo) {
            abort(404, 'Archivo no encontrado');
        }

        return $this->fileStorage->get(self::DOCUMENTS_PATH . $expense->photo);
    }

    /**
     * Get amount by expense type for a single detail
     */
    public function getAmountByExpenseType(int $payrollDetailId, string $type): float
    {
        $map = [
            PayrollExpenseType::CTS->value => ['id' => 17, 'method' => 'getAmountByMonetaryIncome'],
            PayrollExpenseType::SCTR_PENSIONARIO->value => ['id' => 10, 'method' => 'getAmountByTaxAndContribution'],
            PayrollExpenseType::SCTR_SALUD->value => ['id' => 14, 'method' => 'getAmountByTaxAndContribution'],
            PayrollExpenseType::AFP->value => ['id' => 4, 'method' => 'getAmountByTaxAndContribution'],
            PayrollExpenseType::ONP->value => ['id' => 6, 'method' => 'getAmountByTaxAndContribution'],
            PayrollExpenseType::SALARIO_BASICO->value => ['id' => 6, 'method' => 'getAmountByMonetaryIncome'],
            PayrollExpenseType::REF_NO_PRINCIPAL->value => ['id' => 12, 'method' => 'getAmountByMonetaryIncome'],
            PayrollExpenseType::BONUS->value => ['id' => 19, 'method' => 'getAmountByMonetaryIncome'],
        ];

        if (!isset($map[$type])) {
            throw new \InvalidArgumentException('No hay monto para ese tipo de gasto');
        }

        $config = $map[$type];
        return $this->{$config['method']}($payrollDetailId, $config['id']);
    }

    /**
     * Get amounts for multiple details
     */
    public function getAmountsByExpenseTypeMassive(array $payrollDetailIds, string $type): array
    {
        return array_map(
            fn(int $id) => [
                'payroll_detail_id' => $id,
                'amount' => $this->getAmountByExpenseType($id, $type),
            ],
            $payrollDetailIds
        );
    }

    /**
     * Get amount from monetary income repository
     */
    private function getAmountByMonetaryIncome(int $payrollDetailId, int $paramId): float
    {
        return $this->monetaryIncomeRepository->getPaidAmount($payrollDetailId, $paramId);
    }

    /**
     * Get amount from tax and contribution repository
     */
    private function getAmountByTaxAndContribution(int $payrollDetailId, int $paramId): float
    {
        return $this->taxAndContributionRepository->getAmount($payrollDetailId, $paramId);
    }
}
