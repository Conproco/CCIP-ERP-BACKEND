<?php

use App\Models\User;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Role\RoleController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/login', [AuthenticatedSessionController::class, 'store']) -> name('login');

// Rutas Protegidas
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Usuarios
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'getUsers']);
        Route::get('/{id}', [UserController::class, 'getUser']); 
        Route::post('/', [UserController::class, 'store']); // Crear 
        Route::put('/{id}', [UserController::class, 'update']); 
        Route::delete('/{id}', [UserController::class, 'delete']);
    });

    //Roles
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'getRoles']);
        Route::get('/modules', [RoleController::class, 'rols_index']); // Mover arriba
        Route::get('/{id}', [RoleController::class, 'details']);       // Mover abajo
        Route::post('/', [RoleController::class, 'store']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'delete']);
    });

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});

