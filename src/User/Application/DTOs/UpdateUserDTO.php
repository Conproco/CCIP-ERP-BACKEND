<?php

namespace Src\User\Application\DTOs;

use Src\User\Domain\ValueObjects\Email;
use Src\User\Domain\ValueObjects\Dni;
use Src\User\Domain\ValueObjects\Phone;

final class UpdateUserDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly Email $email,
        public readonly Dni $dni,
        public readonly Phone $phone,
        public readonly string $platform,
        public readonly ?int $roleId = null,
        public readonly ?int $areaId = null,
        public readonly ?string $password = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int)$data['id'],
            name: $data['name'],
            email: new Email($data['email']), 
            dni: new Dni($data['dni']),
            phone: new Phone($data['phone']),
            platform: $data['platform'] ?? '',
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
}
