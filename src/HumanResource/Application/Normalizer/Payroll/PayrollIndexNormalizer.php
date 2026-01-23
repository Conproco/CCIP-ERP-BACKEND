<?php

namespace Src\HumanResource\Application\Normalizer\Payroll;

use Src\HumanResource\Application\Dto\Payroll\PayrollIndexDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PayrollIndexNormalizer
{
    public function normalize(LengthAwarePaginator $payrolls): PayrollIndexDto
    {
        $pagination = [
            'current_page' => $payrolls->currentPage(),
            'last_page' => $payrolls->lastPage(),
            'per_page' => $payrolls->perPage(),
            'total' => $payrolls->total(),
            'from' => $payrolls->firstItem(),
            'to' => $payrolls->lastItem(),
        ];

        return new PayrollIndexDto($payrolls, $pagination);
    }
}
