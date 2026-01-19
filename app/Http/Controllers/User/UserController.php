<?php

namespace App\Http\Controllers\User;


use Src\User\Application\DTOs\UpdateUserDTO;
use Src\User\Application\DTOs\UserFiltersDTO;
use Src\User\Application\DTOs\UserResponseDTO;
use Src\User\Application\Services\UserService;
use Src\Role\Application\Services\RoleService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest\UpdateUserRequest;
use Illuminate\Http\Request;
use Src\User\Application\DTOs\CreateUserDTO;
use App\Http\Requests\UserRequest\CreateUserRequest;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected RoleService $roleService
    ) {}

    public function index_user(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json([]);
        }
        
    }

    public function getUsers(Request $request)
    {
        $filters = UserFiltersDTO::fromArray($request->only(['platforms', 'searchQuery']));
        $data = $this->userService->paginate($filters);
        return response()->json($data, 200);
    }

    public function linkEmployee(Request $request, int $userId)
    {
        $user = $this->userService->find($userId);
        $employee = $this->userService->linkEmployeeByDni($userId, $user->dni->value());
        
        if ($employee) {
            return response()->json($employee, 200);
        }
        
        return response()->json(
            "El usuario no tiene un empleado con quien vincular",
            404
        );
    }

    public function edit(Request $request, $id)
    {
        $data = [
            'userId' => $id
        ];
        if ($request->wantsJson()) {
            return response()->json($data);
        }
    }

    public function getUser($id)
    {
        $user = $this->userService->find($id);
        $role = null;
        if ($user->roleId) {
            $roleEntity = $this->roleService->find($user->roleId);
            $role = [
                'id' => $roleEntity->id,
                'name' => $roleEntity->name->value(),
                'description' => $roleEntity->description->value(),
            ];
        }
        $area = $user->areaId ? $this->userService->getArea($user->areaId) : null;
        
        $response = UserResponseDTO::fromEntity($user, $role, $area);
        return response()->json($response->toArray(), 200);
    }

    public function getConstants()
    {
        $roles = $this->roleService->getAllExceptAdmin();
        $data = [
            'rols' => $roles,
            'areas' => $this->userService->getAllAreas()
        ];
        return response()->json($data, 200);
    }

    public function store(CreateUserRequest $request)
    {
        $dto = CreateUserDTO::fromArray($request->validated());
        $user = $this->userService->create($dto);
        
        $response = UserResponseDTO::fromEntity($user);
        return response()->json($response->toArray(), 201);
    }

    public function update(UpdateUserRequest $request, $id)
    {   
        $currentUser = $this->userService->find($id);
        $data = array_merge([
            'id' => $id,
            'name' => $currentUser->name,
            'email' => $currentUser->email->value(),
            'dni' => $currentUser->dni->value(),
            'phone' => $currentUser->phone->value(),
            'platform' => $currentUser->platform,
            'role_id' => $currentUser->roleId,
            'area_id' => $currentUser->areaId,
        ], $request->validated());

        $dto = UpdateUserDTO::fromArray($data);
        $user = $this->userService->update($dto);
        
        $response = UserResponseDTO::fromEntity($user);
        return response()->json($response->toArray(), 200);
    }

    public function delete(Request $request, $id)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);
        
        $this->userService->delete($id, $request->password);
        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }

    public function details(Request $request, $id)
    {
        $user = $this->userService->find($id);
        $role = null;
        if ($user->roleId) {
            $roleEntity = $this->roleService->find($user->roleId);
            $role = [
                'id' => $roleEntity->id,
                'name' => $roleEntity->name->value(),
                'description' => $roleEntity->description->value(),
            ];
        }
        $area = $user->areaId ? $this->userService->getArea($user->areaId) : null;
        
        $response = UserResponseDTO::fromEntity($user, $role, $area);
        return response()->json($response->toArray(), 200);
    }
}
