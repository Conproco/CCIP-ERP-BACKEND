<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{

    public function handle(Request $request, Closure $next, $functionality = null): Response
    {
        if (!auth()->check()) {
            abort(401, 'logueate p.');
        }
        $user = auth()->user();

        // Se ha eliminado el bypass de Super Admin (ID 1) para asegurar que 
        // todos los roles tengan sus funcionalidades explícitamente asignadas.

        // Tomamos el nombre de la ruta si no se pasa funcionalidad por parámetro
        $keyToValidate = $functionality ?? $request->route()->getName();

        if (!$keyToValidate) {
            // Si la ruta no tiene nombre, la dejamos pasar pero registramos un warning 
            // para que el desarrollador sepa que debe nombrarla.
            Log::warning("Ruta sin nombre detectada en middleware de permisos: " . $request->path());
            return $next($request);
        }

        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }

        // Soporte para jerarquía por puntos (ej: see_users_table.search -> valida contra see_users_table o see_users_table.search)
        $keys = [$keyToValidate];
        if (str_contains($keyToValidate, '.')) {
            $keys[] = explode('.', $keyToValidate)[0];
        }

        $hasAccess = $user->role->functionalities()
            ->whereIn('key_name', $keys)
            ->exists();

        Log::info('Permission Check', [
            'user_id' => $user->id,
            'role' => $user->role->name,
            'keys_checked' => $keys,
            'has_access' => $hasAccess
        ]);

        if ($hasAccess) {
            return $next($request);
        }

        abort(403, "No tienes permiso funcional: {$keyToValidate}");
    }
}