<?php

namespace Src\HumanResource\Application\Dto\ExternalEmployees;

use Illuminate\Http\UploadedFile;

class UpdateExternalEmployeeDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $lastname,
        public readonly int $costLineId,
        public readonly ?string $gender = null,
        public readonly ?string $address = null,
        public readonly ?string $birthdate = null,
        public readonly string $dni,
        public readonly string $email,
        public readonly ?string $emailCompany = null,
        public readonly ?string $phone1 = null,
        public readonly ?float $salary = null,
        public readonly ?int $sctr = null,
        public readonly ?string $lPolicy = null,
        public readonly ?string $sctrExpDate = null,
        public readonly ?string $policyExpDate = null,
        public readonly ?UploadedFile $croppedImage = null,
        public readonly ?UploadedFile $curriculumVitae = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'lastname' => $this->lastname,
            'cost_line_id' => $this->costLineId,
            'gender' => $this->gender,
            'address' => $this->address,
            'birthdate' => $this->birthdate,
            'dni' => $this->dni,
            'email' => $this->email,
            'email_company' => $this->emailCompany,
            'phone1' => $this->phone1,
            'salary' => $this->salary,
            'sctr' => $this->sctr,
            'l_policy' => $this->lPolicy,
            'sctr_exp_date' => $this->sctrExpDate,
            'policy_exp_date' => $this->policyExpDate,
        ];
    }
}
