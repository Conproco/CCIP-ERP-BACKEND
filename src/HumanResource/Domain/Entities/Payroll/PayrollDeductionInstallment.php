<?php

namespace Src\HumanResource\Domain\Entities\Payroll;

class PayrollDeductionInstallment
{
    public function __construct(
        private ?int $id = null,
        private ?string $approximatePaymentDate = null,
        private ?string $depositVoucher = null,
        private float $amount = 0.0,
        private string $paymentStatus = 'Pendiente',
        private ?string $operationDate = null,
        private ?string $operationNumber = null,
        private ?int $employeeId = null,
        private ?int $payrollDeductionId = null,
        private ?int $payrollDetailMonetaryDiscountId = null,
        private ?int $generalExpenseId = null,
        private ?string $createdAt = null,
        private ?string $updatedAt = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApproximatePaymentDate(): ?string
    {
        return $this->approximatePaymentDate;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getPaymentStatus(): string
    {
        return $this->paymentStatus;
    }

    public function getPayrollDeductionId(): ?int
    {
        return $this->payrollDeductionId;
    }

    public function getEmployeeId(): ?int
    {
        return $this->employeeId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'approximate_payment_date' => $this->approximatePaymentDate,
            'deposit_voucher' => $this->depositVoucher,
            'amount' => $this->amount,
            'payment_status' => $this->paymentStatus,
            'operation_date' => $this->operationDate,
            'operation_number' => $this->operationNumber,
            'employee_id' => $this->employeeId,
            'payroll_deduction_id' => $this->payrollDeductionId,
            'payroll_detail_monetary_discount_id' => $this->payrollDetailMonetaryDiscountId,
            'general_expense_id' => $this->generalExpenseId,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
