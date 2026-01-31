<?php

declare(strict_types=1);

namespace Src\HumanResource\Application\Dto\Payroll;

use Illuminate\Http\UploadedFile;

final readonly class UpdatePayrollDetailExpenseDto
{
    public function __construct(
        public int $id,
        public int $payrollDetailId,
        public ?int $generalExpenseId,
        public string $employeeName,
        public ?UploadedFile $photo,
        public string $expenseType,
        public ?string $operationNumber,
        public ?string $operationDate,
        public ?string $docDate,
        public ?string $docNumber,
        public string $typeDoc,
        public float $amount,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'payroll_detail_id' => $this->payrollDetailId,
            'general_expense_id' => $this->generalExpenseId,
            'employee_name' => $this->employeeName,
            'expense_type' => $this->expenseType,
            'operation_number' => $this->operationNumber,
            'operation_date' => $this->operationDate,
            'doc_date' => $this->docDate,
            'doc_number' => $this->docNumber,
            'type_doc' => $this->typeDoc,
            'amount' => $this->amount,
        ], fn($v) => $v !== null);
    }
}
