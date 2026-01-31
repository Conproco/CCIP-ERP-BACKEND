<?php

namespace Src\HumanResource\Domain\Entities\Payroll;

class PayrollDeduction
{
    public function __construct(
        private ?int $id = null,
        private ?string $reason = null,
        private ?string $depositVoucher = null,
        private ?string $operationNumber = null,
        private ?string $operationDate = null,
        private ?string $authorizationFile = null,
        private ?string $observations = null,
        private ?int $employeeId = null,
        private float $totalAmount = 0.0,
        private string $status = 'Pendiente',
        private ?string $createdAt = null,
        private ?string $updatedAt = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getDepositVoucher(): ?string
    {
        return $this->depositVoucher;
    }

    public function getOperationNumber(): ?string
    {
        return $this->operationNumber;
    }

    public function getOperationDate(): ?string
    {
        return $this->operationDate;
    }

    public function getAuthorizationFile(): ?string
    {
        return $this->authorizationFile;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function getEmployeeId(): ?int
    {
        return $this->employeeId;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'reason' => $this->reason,
            'deposit_voucher' => $this->depositVoucher,
            'operation_number' => $this->operationNumber,
            'operation_date' => $this->operationDate,
            'authorization_file' => $this->authorizationFile,
            'observations' => $this->observations,
            'employee_id' => $this->employeeId,
            'total_amount' => $this->totalAmount,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
