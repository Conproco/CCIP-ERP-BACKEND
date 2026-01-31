<?php

declare(strict_types=1);

namespace Src\HumanResource\Application\Normalizer\Payroll;

use App\Http\Requests\HumanResource\Payroll\MassiveUpdateExpenseRequest;
use Carbon\Carbon;
use Src\HumanResource\Application\Dto\Payroll\MassiveUpdateExpenseDto;

class MassiveUpdateExpenseNormalizer
{
    public function normalize(MassiveUpdateExpenseRequest $request): MassiveUpdateExpenseDto
    {
        return new MassiveUpdateExpenseDto(
            ids: $request->validated('ids'),
            operationDate: Carbon::parse($request->validated('operation_date'))->format('Y-m-d'),
            operationNumber: (string) $request->validated('operation_number'),
        );
    }
}
