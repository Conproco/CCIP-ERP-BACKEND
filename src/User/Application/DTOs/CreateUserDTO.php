<?php

namespace Src\User\Application\DTOs;

use Src\User\Domain\ValueObjects\Email;
use Src\User\Domain\ValueObjects\Dni;
use Src\User\Domain\ValueObjects\Phone;

final class CreateUserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly Email $email,
        public readonly Dni $dni,
        public readonly Phone $phone,
        public readonly string $platform,
        public readonly string $password,
        public readonly ?int $roleId = null,
        public readonly ?int $areaId = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: new Email($data['email']),
            dni: new Dni($data['dni']),
            phone: new Phone($data['phone']),
            platform: $data['platform'],
            password: $data['password'],
            roleId: $data['role_id'] ?? null,
            areaId: $data['area_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email->value(),
            'dni' => $this->dni->value(),
            'phone' => $this->phone->value(),
            'platform' => $this->platform,
            'password' => $this->password,
            'role_id' => $this->roleId,
            'area_id' => $this->areaId,
        ];
    }
}
