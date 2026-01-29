<?php

declare(strict_types=1);

namespace Src\HumanResource\Application\Services\Payroll;

use Src\HumanResource\Domain\Ports\Repositories\Payroll\PayrollRepositoryInterface;

class PayrollCommandService
{
    public function __construct(
        private PayrollRepositoryInterface $payrollRepository
    ) {
    }

    /**
     * Update payroll state to true (closed/completed)
     */
    public function updateState(int $payrollId): object
    {
        return $this->payrollRepository->updateState($payrollId, true);
    }
}
