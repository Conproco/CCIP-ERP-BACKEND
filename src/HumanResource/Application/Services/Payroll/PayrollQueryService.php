<?php

namespace Src\HumanResource\Application\Services\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollRepositoryInterface;
use Src\HumanResource\Application\Normalizer\Payroll\PayrollIndexNormalizer;
use Src\HumanResource\Application\Dto\Payroll\PayrollIndexDto;

class PayrollQueryService
{
    public function __construct(
        private PayrollRepositoryInterface $payrollRepository,
        private PayrollIndexNormalizer $indexNormalizer
    ) {
    }

    /**
     * Get paginated list of payrolls with calculated totals
     */
    public function getIndexData(int $perPage = 15): PayrollIndexDto
    {
        $payrolls = $this->payrollRepository->getAllPaginated($perPage);
        return $this->indexNormalizer->normalize($payrolls);
    }
}
