<?php


namespace Src\HumanResource\Domain\Entities\Employees;

use Src\Shared\Domain\ValueObjects\Dni;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Telefono;

class Employee
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $lastname,
        private string $gender,
        private string $stateCivil,
        private string $birthdate,
        private Dni $dni,
        private Email $email,
        private ?Email $emailCompany,
        private Telefono $phone1,
        private ?string $croppedImage = null,
        private ?string $lPolicy = null,
        private ?string $sctrExpDate = null,
        private ?string $policyExpDate = null,
        private ?int $userId = null,
    ) {}

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getFullName(): string
    {
        return $this->name . ' ' . $this->lastname;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getStateCivil(): string
    {
        return $this->stateCivil;
    }

    public function getBirthdate(): string
    {
        return $this->birthdate;
    }

    public function getDni(): Dni
    {
        return $this->dni;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getEmailCompany(): ?Email
    {
        return $this->emailCompany;
    }

    public function getPhone1(): Telefono
    {
        return $this->phone1;
    }

    public function getCroppedImage(): ?string
    {
        return $this->croppedImage;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function updatePersonalInfo(
        string $name,
        string $lastname,
        string $gender,
        string $stateCivil,
        string $birthdate
    ): void {
        $this->name = $name;
        $this->lastname = $lastname;
        $this->gender = $gender;
        $this->stateCivil = $stateCivil;
        $this->birthdate = $birthdate;
    }

    public function updateContactInfo(
        Email $email,
        ?Email $emailCompany,
        Telefono $phone1
    ): void {
        $this->email = $email;
        $this->emailCompany = $emailCompany;
        $this->phone1 = $phone1;
    }

    public function updateProfileImage(?string $croppedImage): void
    {
        $this->croppedImage = $croppedImage;
    }

    // Domain logic
    public function isAdult(): bool
    {
        $birthdate = new \DateTime($this->birthdate);
        $today = new \DateTime();
        $age = $today->diff($birthdate)->y;
        return $age >= 18;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'gender' => $this->gender,
            'state_civil' => $this->stateCivil,
            'birthdate' => $this->birthdate,
            'dni' => $this->dni->value(),
            'email' => $this->email->value(),
            'email_company' => $this->emailCompany?->value(),
            'phone1' => $this->phone1->value(),
            'cropped_image' => $this->croppedImage,
            'l_policy' => $this->lPolicy,
            'sctr_exp_date' => $this->sctrExpDate,
            'policy_exp_date' => $this->policyExpDate,
            'user_id' => $this->userId,
        ];
    }
}
