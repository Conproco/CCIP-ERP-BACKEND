<?php

namespace Src\HumanResource\Application\Services\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollRepositoryInterface;
use Src\HumanResource\Application\Data\Payroll\PayrollPaginatedData;

use Src\HumanResource\Application\Data\Payroll\PayrollResponseData;
use Src\HumanResource\Application\Data\Payroll\PayrollData;
use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollDetailRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class PayrollQueryService
{
    public function __construct(
        private PayrollRepositoryInterface $payrollRepository,
        private PayrollDetailRepositoryInterface $payrollDetailRepository
    ) {
    }

    /**
     * Get paginated list of payrolls with calculated totals
     */
    public function getIndexData(int $perPage = 15): PayrollPaginatedData
    {
        $payrolls = $this->payrollRepository->getAllPaginated($perPage);
        return PayrollPaginatedData::fromPaginator($payrolls);
    }

    /**
     * Get a specific payroll by ID with associated static data
     */
    public function find(int $id): PayrollResponseData
    {
        $payroll = $this->payrollRepository->find($id);

        if (!$payroll) {
            throw new ModelNotFoundException();
        }

        return PayrollResponseData::create(
            PayrollData::fromModel($payroll)
        );
    }

    /**
     * Get payroll details with calculated fields and optional filtering
     */
    public function getPayrollDetails(
        int $payrollId,
        ?string $search = null,
        ?array $pensionTypes = null,
        ?array $stateTypes = null
    ): Collection {
        $details = $this->payrollDetailRepository->getDetailsByPayrollWithFilters(
            $payrollId,
            $search,
            $pensionTypes
        );

        // Filter by state types in memory (post-query)
        // Repository already computes new_totals, verified, net_pay
        if ($stateTypes && count($stateTypes) > 0 && count($stateTypes) < 5) {
            $details = $details->filter(function ($item) use ($stateTypes) {
                return in_array($item->verified['state'], $stateTypes);
            })->values();
        }

        return $details;
    }
}
