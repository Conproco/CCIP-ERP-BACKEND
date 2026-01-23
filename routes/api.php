<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Inventory\ProductController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/login', [AuthenticatedSessionController::class, 'store']) -> name('login');

// Rutas Protegidas
Route::middleware(['auth:sanctum'])->group(function () {
    //me para permmisos siempre
   


    // Usuarios
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'getUsers']);
        Route::get('/constants', [UserController::class, 'getConstants']);
        Route::get('/{id}', [UserController::class, 'getUser']); 
        Route::post('/', [UserController::class, 'store']); // Crear }
        Route::post('/{userId}/restore', [UserController::class, 'restore']);
        Route::put('/{id}', [UserController::class, 'update']); 
        Route::delete('/{id}', [UserController::class, 'delete']);
        
    });

     Route::get('/auth/me', [AuthenticatedSessionController::class, 'me']);

    //Roles
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'getRoles']);
        Route::get('/modules', [RoleController::class, 'rols_index']); 
        Route::get('/{id}', [RoleController::class, 'details']);       
        Route::post('/', [RoleController::class, 'store']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'delete']);
    });

    //Imports de otros modulos 
    require __DIR__.'/HumanResource/employees.php';
    require __DIR__.'/Products/Products.php';

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});

