<?php

namespace App\Http\Middleware;

use App\Models\FunctionalityPermission;
use App\Models\Permission;
use Closure;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permissions = null): Response
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        $user = auth()->user();

        // Super Admin (rol_id = 1) gets all permissions
        if ($user->role_id === 1) {
            return $next($request);
        }
        $permissionName = $permissions ?? $request->route()->getName();

        if (!$permissionName) {
            return response()->json(['message' => 'Route is not protected by an explicit permission name.'], 403);
        }

        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        
        $functionalities = $user->role->functionalities()->pluck('functionalities.id');
        
        $permission = Permission::where('name', $permissionName)->first();

        if (!$permission) {
            return response()->json(['message' => "Permission '{$permissionName}' not defined in system."], 403);
        }

        $hasPermission = FunctionalityPermission::whereIn('functionality_id', $functionalities)
            ->where('permission_id', $permission->id)
            ->exists();

        if ($hasPermission) {
            return $next($request);
        }

        return response()->json(['message' => 'You do not have the required permissions to perform this action.'], 403);
    }
}
