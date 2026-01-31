<?php

namespace Src\HumanResource\Application\Normalizer\Payroll;

use App\Http\Requests\HumanResource\Payroll\GetAmountByExpenseTypeRequest;
use Src\HumanResource\Application\Dto\Payroll\GetAmountByExpenseTypeDto;

class GetAmountByExpenseTypeRequestNormalizer
{
    public function normalize(GetAmountByExpenseTypeRequest $request): GetAmountByExpenseTypeDto
    {
        $validated = $request->validated();

        return new GetAmountByExpenseTypeDto(
            payrollDetailId: (int) $validated['payroll_detail_id'],
            type: $validated['type']
        );
    }
}
