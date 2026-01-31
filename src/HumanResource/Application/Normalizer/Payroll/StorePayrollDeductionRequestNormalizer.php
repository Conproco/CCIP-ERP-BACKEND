<?php

namespace Src\HumanResource\Application\Normalizer\Payroll;

use App\Http\Requests\HumanResource\Payroll\StorePayrollDeductionRequest;
use Src\HumanResource\Application\Dto\Payroll\StorePayrollDeductionDto;

class StorePayrollDeductionRequestNormalizer
{
    public function normalize(StorePayrollDeductionRequest $request): StorePayrollDeductionDto
    {
        return new StorePayrollDeductionDto(
            reason: $request->input('reason'),
            depositVoucher: $request->file('deposit_voucher'),
            operationNumber: $request->input('operation_number'),
            operationDate: $request->input('operation_date'),
            authorizationFile: $request->file('authorization_file'),
            observations: $request->input('observations'),
            employeeId: (int) $request->input('employee_id'),
            installmentsQuantity: (int) $request->input('installments_quantity'),
            amount: (float) $request->input('amount'),
            startDate: $request->input('start_date'),
        );
    }
}
