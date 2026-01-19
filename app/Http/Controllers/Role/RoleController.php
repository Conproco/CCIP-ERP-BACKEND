<?php

namespace App\Http\Controllers\Role;

use Src\Role\Application\DTOs\CreateRoleDTO;
use Src\Role\Application\DTOs\UpdateRoleDTO;
use Src\Role\Application\DTOs\RoleFiltersDTO;
use Src\Role\Application\DTOs\RoleResponseDTO;
use Src\Role\Application\Services\RoleService;
use App\Http\Controllers\Controller;
use App\Http\Requests\RolRequest\CreateRolRequest;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService
    ) {}
    //cambiamos rols_index a getModules
    public function rols_index(Request $request)
    {
        $data = ['modules' => $this->roleService->getModulesWithFunctionalities()];
        return response()->json($data);
    }

    public function getRols(Request $request)
    {
        $filters = RoleFiltersDTO::fromArray($request->only(['searchQuery']));
        $data = $this->roleService->paginate($filters);
        return response()->json($data, 200);
    }

    public function store(CreateRolRequest $request)
    {
        $validatedData = $request->validated();
        $dto = CreateRoleDTO::fromArray($validatedData);
        $role = $this->roleService->create($dto);
        
        $response = RoleResponseDTO::fromEntity($role);
        return response()->json($response->toArray(), 200);
    }

    public function update(CreateRolRequest $request, $rol_id)
    {
        $currentRole = $this->roleService->getWithFunctionalities($rol_id);
        $validatedData = $request->validated();
        $validatedData = array_merge([
            'id' => $rol_id,
            'name' => $currentRole->name->value(),
            'description' => $currentRole->description->value(),
            'functionalities' => $currentRole->functionalities,
        ], $request->validated());
        
        $dto = UpdateRoleDTO::fromArray($validatedData);
        $role = $this->roleService->update($dto);
        
        $response = RoleResponseDTO::fromEntity($role);
        return response()->json($response->toArray(), 200);
    }

    public function delete($id)
    {
        $this->roleService->delete($id);
        return response()->json(['message' => 'Rol eliminado correctamente'], 200);
    }


    //opcion previsualizar roles en front 
    public function details($id)
    {   //seteamos los roles en entero para evitar problemas de tipado
        $role = $this->roleService->getWithFunctionalities((int)$id);
        $response = RoleResponseDTO::fromEntity($role);
        
        return response()->json($response->toArray(), 200);
    }
}
