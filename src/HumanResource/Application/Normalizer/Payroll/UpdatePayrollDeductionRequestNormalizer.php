<?php

namespace Src\HumanResource\Application\Normalizer\Payroll;

use App\Http\Requests\HumanResource\Payroll\UpdatePayrollDeductionRequest;
use Src\HumanResource\Application\Dto\Payroll\UpdatePayrollDeductionDto;

class UpdatePayrollDeductionRequestNormalizer
{
    public function normalize(UpdatePayrollDeductionRequest $request, int $deductionId): UpdatePayrollDeductionDto
    {
        return new UpdatePayrollDeductionDto(
            id: $deductionId,
            reason: $request->input('reason'),
            operationNumber: $request->input('operation_number'),
            operationDate: $request->input('operation_date'),
            observations: $request->input('observations'),
            employeeId: (int) $request->input('employee_id'),
        );
    }
}
