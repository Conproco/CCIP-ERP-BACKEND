<?php

namespace Src\HumanResource\Domain\Entities\Employees;

class Address
{
    public function __construct(
        private ?int $id,
        private int $employeeId,
        private string $streetAddress,
        private string $department,
        private string $province,
        private string $district,
    ) {}

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }

    public function getStreetAddress(): string
    {
        return $this->streetAddress;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function getProvince(): string
    {
        return $this->province;
    }

    public function getDistrict(): string
    {
        return $this->district;
    }

    public function getFullAddress(): string
    {
        return sprintf(
            "%s, %s, %s, %s",
            $this->streetAddress,
            $this->district,
            $this->province,
            $this->department
        );
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function updateAddress(
        string $streetAddress,
        string $department,
        string $province,
        string $district
    ): void {
        $this->streetAddress = $streetAddress;
        $this->department = $department;
        $this->province = $province;
        $this->district = $district;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'street_address' => $this->streetAddress,
            'department' => $this->department,
            'province' => $this->province,
            'district' => $this->district,
        ];
    }
}
