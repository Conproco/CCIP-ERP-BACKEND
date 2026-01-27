<?php

namespace Src\User\Infrastructure\Persistence;

use Src\User\Domain\Entities\UserEntity;
use Src\User\Domain\Repositories\UserRepository;
use Src\User\Domain\ValueObjects\Email;
use Src\User\Domain\ValueObjects\Dni;
use Src\User\Domain\ValueObjects\Phone;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EloquentUserRepository implements UserRepository
{
    public function find(int $id): ?UserEntity
    {
        $user = User::find($id);
        
        return $user ? $this->toEntity($user) : null;
    }

    public function findByEmail(string $email): ?UserEntity
    {
        $user = User::where('email', $email)->first();
        
        return $user ? $this->toEntity($user) : null;
    }

    public function findByDni(string $dni): ?UserEntity
    {
        $user = User::where('dni', $dni)->first();
        
        return $user ? $this->toEntity($user) : null;
    }

    public function all(array $filters = []): array
    {
        $query = $this->applyFilters(User::query(), $filters);
        
        return $query->get()
            ->map(fn($user) => $this->toEntity($user))
            ->toArray();
    }

    public function paginate(array $filters = [], int $perPage = 15): mixed
    {
        $query = User::with(['role:id,name']) 
            ->select(['id', 'name', 'platform', 'email', 'dni', 'phone', 'role_id', 'deleted_at']); // Añadimos deleted_at

        $query = $this->applyFilters($query, $filters);

        return $query->paginate($perPage)->through(fn($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'platform' => $user->platform,
            'role' => $user->role?->name,
            'email' => $user->email,
            'dni' => $user->dni,
            'phone' => $user->phone,
            'deleted_at' => $user->deleted_at,
        ]);
    }

    public function save(UserEntity $user): UserEntity
    {
        $data = $user->toArray();
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $model = User::create($data);
        
        return $this->toEntity($model);
    }

    public function update(int $id, array $data): UserEntity
    {
        $user = User::findOrFail($id);
        
        // Si el password está presente y no es null, hashearlo
        if (isset($data['password']) && $data['password'] !== null) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Si es null o no está presente, eliminarlo del array para no actualizarlo
            unset($data['password']);
        }

        $user->update($data);
        
        return $this->toEntity($user->fresh());
    }

    public function delete(int $id): bool
    {
        $user = User::findOrFail($id);
        
        // Eliminar tokens de sanctum
        $user->tokens()->delete();
        
        return $user->delete();
    }

    public function findWithTrashed(int $id): ?UserEntity
    {
        $user = User::withTrashed()->find($id);
        
        return $user ? $this->toEntity($user) : null;
    }
    
    public function restore(int $id): bool
    {
        $user = User::withTrashed()->findOrFail($id);
        return $user->restore();
    }

    public function onlyTrashed(array $filters = [], int $perPage = 15): mixed
    {
        $query = User::onlyTrashed() 
            ->with(['role:id,name']) // Filtra solo eliminados lógicamente
            ->select(['id', 'name', 'platform', 'email', 'dni', 'phone', 'role_id', 'deleted_at']);

        $query = $this->applyFilters($query, $filters);

        return $query->paginate($perPage)->through(fn($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'platform' => $user->platform,
            'role' => $user->role?->name,
            'email' => $user->email,
            'dni' => $user->dni,
            'phone' => $user->phone,
            'deleted_at' => $user->deleted_at,
        ]);
    }
    
    public function exists(string $field, string $value, ?int $excludeId = null): bool
    {
        $query = User::where($field, $value);
        
        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    public function getWithRelations(int $id, array $relations = []): ?UserEntity
    {
        $user = User::with($relations)->find($id);
        
        return $user ? $this->toEntity($user) : null;
    }

    private function applyFilters($query, array $filters)
    {
        if (isset($filters['includeTrashed']) && $filters['includeTrashed']) {
            $query->withTrashed();
        }
    
        if (isset($filters['searchQuery'])) {
            $searchQuery = $filters['searchQuery'];
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%$searchQuery%")
                    ->orWhere('dni', 'like', "%$searchQuery%")
                    ->orWhere('email', 'like', "%$searchQuery%");
            });
        }

        if (isset($filters['platform'])) {
            $query->whereIn('platform', $filters['platform']);
        }

        if (isset($filters['role_id'])) {
            $query->where('role_id', $filters['role_id']);
        }

        if (isset($filters['area_id'])) {
            $query->where('area_id', $filters['area_id']);
        }

        if (isset($filters['has_employee'])) {
            if ($filters['has_employee']) {
                $query->whereHas('employee');
            } else {
                $query->whereDoesntHave('employee');
            }
        }

        return $query;
    }

    private function toEntity(User $model): UserEntity
    {
        return new UserEntity(
            id: $model->id,
            name: $model->name,
            email: new Email($model->email),
            dni: new Dni($model->dni),
            phone: new Phone($model->phone),
            platform: $model->platform,
            roleId: $model->role_id,
            areaId: $model->area_id,
            password: $model->password,
        );
    }

    public function linkEmployeeByDni(int $userId, string $dni): ?array
    {
        $employee = \App\Models\Employee::select('id', 'dni', 'name')
            ->where('dni', $dni)
            ->first();
        
        if ($employee) {
            $employee->update(['user_id' => $userId]);
            return $employee->toArray();
        }
        
        return null;
    }

    public function getArea(int $areaId): ?array
    {
        $area = \App\Models\Area::find($areaId);
        return $area ? $area->toArray() : null;
    }

    public function getAllAreas(): array
    {
        return \App\Models\Area::select('id', 'name')->get()->toArray();
    }

    public function search(string $search, array $fields = [], bool $includeTrashed = false): array
    {
        $query = User::with(['role:id,name'])
            ->select(['id', 'name', 'platform', 'email', 'dni', 'phone', 'role_id', 'deleted_at']);

        
        if ($includeTrashed) {
            $query->withTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        if (!empty($fields) && !empty($search)) {
            $query->where(function ($q) use ($search, $fields) {
                foreach ($fields as $field) {
                    $q->orWhere($field, 'like', "%$search%");
                }
            });
        }

        return $query->orderBy('name')
            ->limit(50)
            ->get()
            ->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'platform' => $user->platform,
                'rol' => $user->role?->name,
                'email' => $user->email,
                'dni' => $user->dni,
                'phone' => $user->phone,
                'deleted_at' => $user->deleted_at
            ])
            ->toArray();
    }
}
