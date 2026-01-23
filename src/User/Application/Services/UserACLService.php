<?php
namespace Src\User\Application\Services;

use App\Models\User;
use App\Models\Module;
use App\Models\Functionality;

class UserACLService
{
    public function getPermissionsTree(User $user): array
    {
        $role = $user->role;
        
        $data = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $role?->name ?? 'N/A'
            ],
            'permissions' => [],
            'modules' => []
        ];

        if (!$role) {
            return $data;
        }

        // 1. Obtener todas las funcionalidades permitidas para este rol
        // Si es admin (ID 1), obtenemos todas las del sistema
        if ($role->id === 1) {
            $functionalities = Functionality::with('permissions')->get();
        } else {
            $functionalities = $role->functionalities()->with('permissions')->get();
        }

        $data['permissions'] = $functionalities->flatMap(function ($func) {
            return $func->permissions->pluck('name');
        })->unique()->values()->toArray();

        // 2. Obtener IDs de sub-módulos y módulos relacionados
        $submoduleIds = $functionalities->pluck('module_id')->unique();
        $submodules = Module::whereIn('id', $submoduleIds)->where('type', 'submodule')->get();

        $moduleIds = $submodules->pluck('parent_id')->unique();
        $modules = Module::whereIn('id', $moduleIds)->where('type', 'module')->get();

        // 3. Construir el árbol jerárquico
        foreach ($modules as $module) {
            $moduleData = [
                'key' => strtoupper(str_replace(' ', '_', $module->name)) . "_MODULE",
                'display_name' => $module->display_name ?? $module->name,
                'submodules' => []
            ];

            $relevantSubmodules = $submodules->where('parent_id', $module->id);

            foreach ($relevantSubmodules as $submodule) {
                $submoduleData = [
                    'key' => $submodule->name, // Ej: user_submodule
                    'functionalities' => []
                ];

                $relevantFuncs = $functionalities->where('module_id', $submodule->id);

                foreach ($relevantFuncs as $func) {
                    $submoduleData['functionalities'][] = [
                        'id'=>$func->id,
                        'key' => $func->key_name,
                        'permissions' => $func->permissions->pluck('name')->toArray()
                    ];
                }

                $moduleData['submodules'][] = $submoduleData;
            }

            $data['modules'][] = $moduleData;
        }

        return $data;
    }
}