<?php

use App\Models\User;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/login', [AuthenticatedSessionController::class, 'store']) -> name('login');

// Rutas Protegidas
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Usuarios
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'getUsers']);
        Route::get('/{id}', [UserController::class, 'getUser']);

        //nuevas rutas users 
        Route::post('/', [UserController::class, 'store']); // Crear 
        Route::put('/{id}', [UserController::class, 'update']); // Editar
        Route::delete('/{id}', [UserController::class, 'delete']);
    });

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});

