<?php

namespace Src\User\Domain\Rules;

use Src\User\Domain\Entities\UserEntity;
use Src\User\Domain\Exceptions\UserAlreadyExistsException;
use Src\User\Domain\Repositories\UserRepository;

class UserRules
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function validateUniqueEmail(string $email, ?int $excludeId = null): void
    {
        if ($this->userRepository->exists('email', $email, $excludeId)) {
            throw new UserAlreadyExistsException('email', $email);
        }
    }

    public function validateUniqueDni(string $dni, ?int $excludeId = null): void
    {
        if ($this->userRepository->exists('dni', $dni, $excludeId)) {
            throw new UserAlreadyExistsException('DNI', $dni);
        }
    }

    public function validateUniquePhone(string $phone, ?int $excludeId = null): void
    {
        if ($this->userRepository->exists('phone', $phone, $excludeId)) {
            throw new UserAlreadyExistsException('teléfono', $phone);
        }
    }

    public function validateUserForCreation(UserEntity $user): void
    {
        $this->validateUniqueEmail($user->email->value());
        $this->validateUniqueDni($user->dni->value());
        $this->validateUniquePhone($user->phone->value());
    }

    public function validateUserForUpdate(UserEntity $user): void
    {
        if ($user->id === null) {
            throw new \InvalidArgumentException('El usuario debe tener un ID para ser actualizado');
        }

        $this->validateUniqueEmail($user->email->value(), $user->id);
        $this->validateUniqueDni($user->dni->value(), $user->id);
        $this->validateUniquePhone($user->phone->value(), $user->id);
    }
}
