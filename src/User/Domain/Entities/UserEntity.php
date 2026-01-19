<?php

namespace Src\User\Domain\Entities;

use Src\User\Domain\ValueObjects\Email;
use Src\User\Domain\ValueObjects\Dni;
use Src\User\Domain\ValueObjects\Phone;

class UserEntity
{
    public function __construct(
        public readonly ?int $id,
        public string $name,
        public Email $email,
        public Dni $dni,
        public Phone $phone,
        public string $platform,
        public ?int $roleId = null,
        public ?int $areaId = null,
        public ?string $password = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int)$data['id'] : null,
            name: $data['name'],
            email: new Email($data['email']),
            dni: new Dni($data['dni']),
            phone: new Phone($data['phone']),
            platform: $data['platform'],
            roleId: isset($data['role_id']) ? (int)$data['role_id'] : null,
            areaId: isset($data['area_id']) ? (int)$data['area_id'] : null,
            password: $data['password'] ?? null,
        );
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email->value(),
            'dni' => $this->dni->value(),
            'phone' => $this->phone->value(),
            'platform' => $this->platform,
            'role_id' => $this->roleId,
            'area_id' => $this->areaId,
        ];

        if ($this->password !== null) {
            $data['password'] = $this->password;
        }

        return $data;
    }

    public function changeRole(int $roleId): void
    {
        $this->roleId = $roleId;
    }

    public function changeArea(int $areaId): void
    {
        $this->areaId = $areaId;
    }

    public function updateProfile(string $name, Email $email, Phone $phone): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }
}
