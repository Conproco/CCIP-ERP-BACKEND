<?php

declare(strict_types=1);

namespace Src\HumanResource\Application\Listeners;

use Src\HumanResource\Domain\Events\PayrollDeleted;
use Src\HumanResource\Application\Services\Payroll\PayrollDeductionInstallmentCommandService;

class RevertInstallmentsOnPayrollDeleted
{
    public function __construct(
        private PayrollDeductionInstallmentCommandService $installmentService
    ) {
    }

    public function handle(PayrollDeleted $event): void
    {
        if (empty($event->discountIds)) {
            return;
        }

        $this->installmentService->revertByDiscountIds($event->discountIds);
    }
}
