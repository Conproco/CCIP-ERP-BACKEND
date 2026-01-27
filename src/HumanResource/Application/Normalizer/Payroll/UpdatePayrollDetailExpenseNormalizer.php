<?php

declare(strict_types=1);

namespace Src\HumanResource\Application\Normalizer\Payroll;

use App\Http\Requests\HumanResource\Payroll\UpdatePayrollDetailExpenseRequest;
use Carbon\Carbon;
use Src\HumanResource\Application\Dto\Payroll\UpdatePayrollDetailExpenseDto;

class UpdatePayrollDetailExpenseNormalizer
{
    public function normalize(UpdatePayrollDetailExpenseRequest $request): UpdatePayrollDetailExpenseDto
    {
        return new UpdatePayrollDetailExpenseDto(
            id: (int) $request->validated('id'),
            payrollDetailId: (int) $request->validated('payroll_detail_id'),
            generalExpenseId: $request->validated('general_expense_id') ? (int) $request->validated('general_expense_id') : null,
            employeeName: $request->validated('employee_name'),
            photo: $request->file('photo'),
            expenseType: $request->validated('expense_type'),
            operationNumber: $request->validated('operation_number'),
            operationDate: $request->validated('operation_date') ? Carbon::parse($request->validated('operation_date'))->format('Y-m-d') : null,
            docDate: $request->validated('doc_date') ? Carbon::parse($request->validated('doc_date'))->format('Y-m-d') : null,
            docNumber: $request->validated('doc_number'),
            typeDoc: $request->validated('type_doc'),
            amount: (float) $request->validated('amount'),
        );
    }
}
