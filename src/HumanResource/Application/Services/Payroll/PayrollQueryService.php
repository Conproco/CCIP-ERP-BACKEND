<?php

namespace Src\HumanResource\Application\Services\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollRepositoryInterface;
use Src\HumanResource\Application\Data\Payroll\PayrollPaginatedData;

use Src\HumanResource\Application\Data\Payroll\PayrollResponseData;
use Src\HumanResource\Application\Data\Payroll\PayrollData;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PayrollQueryService
{
    public function __construct(
        private PayrollRepositoryInterface $payrollRepository
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
}
