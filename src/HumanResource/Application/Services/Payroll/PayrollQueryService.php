<?php

namespace Src\HumanResource\Application\Services\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollRepositoryInterface;
use Src\HumanResource\Application\Data\Payroll\PayrollPaginatedData;

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
}
