<?php

namespace Src\HumanResource\Domain\Entities\ExternalEmployees;

class ExternalEmployee
{
    public function __construct(
        private ?int $id = null,
        private ?string $name = null,
        private ?string $lastname = null,
        private ?int $costLineId = null,
        private ?string $croppedImage = null,
        private ?string $gender = null,
        private ?string $address = null,
        private ?string $birthdate = null,
        private ?string $dni = null,
        private ?string $email = null,
        private ?string $emailCompany = null,
        private ?string $phone1 = null,
        private ?float $salary = null,
        private ?string $sctr = null,
        private ?string $curriculumVitae = null,
        private ?string $lPolicy = null,
        private ?string $sctrExpDate = null,
        private ?string $policyExpDate = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function getCostLineId(): ?int
    {
        return $this->costLineId;
    }

    public function getCroppedImage(): ?string
    {
        return $this->croppedImage;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getEmailCompany(): ?string
    {
        return $this->emailCompany;
    }

    public function getPhone1(): ?string
    {
        return $this->phone1;
    }

    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function getSctr(): ?string
    {
        return $this->sctr;
    }

    public function getCurriculumVitae(): ?string
    {
        return $this->curriculumVitae;
    }

    public function getLPolicy(): ?string
    {
        return $this->lPolicy;
    }

    public function getSctrExpDate(): ?string
    {
        return $this->sctrExpDate;
    }

    public function getPolicyExpDate(): ?string
    {
        return $this->policyExpDate;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'cost_line_id' => $this->costLineId,
            'cropped_image' => $this->croppedImage,
            'gender' => $this->gender,
            'address' => $this->address,
            'birthdate' => $this->birthdate,
            'dni' => $this->dni,
            'email' => $this->email,
            'email_company' => $this->emailCompany,
            'phone1' => $this->phone1,
            'salary' => $this->salary,
            'sctr' => $this->sctr,
            'curriculum_vitae' => $this->curriculumVitae,
            'l_policy' => $this->lPolicy,
            'sctr_exp_date' => $this->sctrExpDate,
            'policy_exp_date' => $this->policyExpDate,
        ];
    }
}
