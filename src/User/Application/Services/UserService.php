<?php

namespace Src\User\Application\Services;
use Src\User\Application\DTOs\CreateUserDTO;
use Src\User\Application\DTOs\UpdateUserDTO;
use Src\User\Application\DTOs\UserFiltersDTO;
use Src\User\Domain\Entities\UserEntity;
use Src\User\Domain\Exceptions\InvalidCredentialsException;
use Src\User\Domain\Exceptions\UserNotFoundException;
use Src\User\Domain\Repositories\UserRepository;
use Src\User\Domain\Rules\UserRules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserRules $userRules
    ) {}

    public function create(CreateUserDTO $dto): UserEntity
    {
        // Crear entidad para validación (sin password hasheado en la validación)
        $userData = $dto->toArray();
        $user = UserEntity::fromArray($userData);
        
        $this->userRules->validateUserForCreation($user);

        // Guardar con todos los datos incluyendo password
        return $this->userRepository->save($user);
    }

    public function update(UpdateUserDTO $dto): UserEntity
    {
        $user = $this->userRepository->find($dto->id);
        
        if (!$user) {
            throw new UserNotFoundException($dto->id);
        }

        $updatedUser = UserEntity::fromArray($dto->toArray());
        
        // Validar unicidad de campos
        $this->userRules->validateUserForUpdate($updatedUser);

        // Preparar datos para actualizar (sin password si no se proporcionó)
        $updateData = $dto->toArray();
        
        return $this->userRepository->update($dto->id, $updateData);
    }

    public function delete(int $id, string $password): bool
    {
        $user = $this->userRepository->find($id);
        
        if (!$user) {
            throw new UserNotFoundException($id);
        }

        // Validar contraseña del usuario AUTENTICADO actual
        $currentUser = Auth::user();
        if (!Hash::check($password, $currentUser->password)) {
            throw new InvalidCredentialsException();
        }

        return $this->userRepository->delete($id);
    }

    public function restore(int $id): bool
    {
        $user = $this->userRepository->findWithTrashed($id);
        
        if (!$user) {
            throw new UserNotFoundException($id);
        }

        return $this->userRepository->restore($id);
    }

    public function find(int $id): UserEntity
    {
        $user = $this->userRepository->find($id);
        
        if (!$user) {
            throw new UserNotFoundException($id);
        }

        return $user;
    }

    public function findByEmail(string $email): ?UserEntity
    {
        return $this->userRepository->findByEmail($email);
    }

    public function findByDni(string $dni): ?UserEntity
    {
        return $this->userRepository->findByDni($dni);
    }

    public function all(UserFiltersDTO $filters): array
    {
        return $this->userRepository->all($filters->toArray());
    }

    public function paginate(UserFiltersDTO $filters, int $perPage = 15): mixed
    {
        return $this->userRepository->paginate($filters->toArray(), $perPage);
    }

    public function getWithRelations(int $id, array $relations = []): UserEntity
    {
        $user = $this->userRepository->getWithRelations($id, $relations);
        
        if (!$user) {
            throw new UserNotFoundException($id);
        }

        return $user;
    }

    public function changeRole(int $userId, int $roleId): UserEntity
    {
        $user = $this->find($userId);
        $user->changeRole($roleId);
        
        return $this->userRepository->update($userId, ['role_id' => $roleId]);
    }

    public function changeArea(int $userId, int $areaId): UserEntity
    {
        $user = $this->find($userId);
        $user->changeArea($areaId);
        
        return $this->userRepository->update($userId, ['area_id' => $areaId]);
    }

    public function linkEmployeeByDni(int $userId, string $dni): ?array
    {
        return $this->userRepository->linkEmployeeByDni($userId, $dni);
    }

    public function getArea(int $areaId): ?array
    {
        return $this->userRepository->getArea($areaId);
    }

    public function getAllAreas(): array
    {
        return $this->userRepository->getAllAreas();
    }
}
