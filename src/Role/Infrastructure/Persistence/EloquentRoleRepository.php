<?php

namespace Src\Role\Infrastructure\Persistence;

use Src\Role\Domain\Entities\RoleEntity;
use Src\Role\Domain\Repositories\RoleRepository;
use Src\Role\Domain\ValueObjects\RoleName;
use Src\Role\Domain\ValueObjects\RoleDescription;
use App\Models\Role;

class EloquentRoleRepository implements RoleRepository
{
    public function find(int $id): ?RoleEntity
    {
        $role = Role::find($id);
        
        return $role ? $this->toEntity($role) : null;
    }

    public function findByName(string $name): ?RoleEntity
    {
        $role = Role::where('name', $name)->first();
        
        return $role ? $this->toEntity($role) : null;
    }

    public function all(array $filters = []): array
    {
        $query = $this->applyFilters(Role::query(), $filters);
        
        // Devolver arrays simples para serialización
        return $query->get()->toArray();
    }

    public function paginate(array $filters = [], int $perPage = 15): mixed
    {
        $query = Role::with('functionalities:id,key_name,display_name')
            ->select(['id', 'name', 'description']);

        $query = $this->applyFilters($query, $filters);

        // Devolver la paginación de Laravel directamente
        // Laravel serializará automáticamente los modelos a JSON
        return $query->paginate($perPage)->withQueryString();
    }

    public function save(RoleEntity $role): RoleEntity
    {
        $data = [
            'name' => $role->name->value(),
            'description' => $role->description->value(),
        ];

        $model = Role::create($data);
        
        // Sincronizar funcionalidades
        if (!empty($role->functionalities)) {
            $model->functionalities()->attach($role->functionalities);
        }

        $model->load('functionalities');
        
        return $this->toEntity($model);
    }

    public function update(int $id, array $data): RoleEntity
    {
        $role = Role::findOrFail($id);
        
        $role->update([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        // Sincronizar funcionalidades
        if (isset($data['functionalities'])) {
            $role->functionalities()->sync($data['functionalities']);
        }

        $role->load('functionalities');
        
        return $this->toEntity($role->fresh(['functionalities']));
    }

    public function delete(int $id): bool
    {
        $role = Role::findOrFail($id);
        
        // Desasociar funcionalidades
        $role->functionalities()->detach();
        
        return $role->delete();
    }

    public function exists(string $field, string $value, ?int $excludeId = null): bool
    {
        $query = Role::where($field, $value);
        
        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function getWithFunctionalities(int $id): ?RoleEntity
    {
        $role = Role::with(['functionalities.permissions'])->find($id);
        
        return $role ? $this->toEntity($role) : null;
    }

    public function getAllExceptAdmin(): array
    {
        return Role::where('name', '!=', 'admin')
            ->get()
            ->map(fn($role) => $this->toEntity($role))
            ->toArray();
    }

    private function applyFilters($query, array $filters)
    {
        if (isset($filters['searchQuery']) && !empty($filters['searchQuery'])) {
            $searchQuery = $filters['searchQuery'];
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }

        return $query;
    }

    private function toEntity(Role $model): RoleEntity
    {
        $functionalities = [];
        $permissions = [];
        
        if ($model->relationLoaded('functionalities')) {
            $allPermissions = collect();
            
            $functionalities = $model->functionalities->map(function($f) use (&$allPermissions) {
                // cargamos permisos de funcionalidades
                if ($f->relationLoaded('permissions')) {
                    $allPermissions = $allPermissions->concat($f->permissions);
                }
                
                return [
                    'id' => $f->id,
                    'key_name' => $f->key_name,
                    'display_name' => $f->display_name
                ];
            })->toArray();

            // Formateamos los permisos y eliminamos duplicados (varias funcionalidades pueden compartir el mismo permiso)
            $permissions = $allPermissions->unique('id')->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
            ])->values()->toArray();
        }

        return new RoleEntity(
            id: $model->id,
            name: new RoleName($model->name),
            description: new RoleDescription($model->description),
            functionalities: $functionalities,
            permissions: $permissions,
            created_at: $model->created_at ? $model->created_at->toDateTimeString() : null,
            updated_at: $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        );
    }

    private function toModel(RoleEntity $entity): array
    {
        return [
            'id' => $entity->id,
            'name' => $entity->name->value(),
            'description' => $entity->description->value(),
        ];
    }

    public function getModulesWithFunctionalities(): array
    {
        return \App\Models\Module::with('submodules.functionalities')
            ->where('type', 'module')
            ->get()
            ->toArray();
    }
}
