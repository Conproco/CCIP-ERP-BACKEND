<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/login', [AuthenticatedSessionController::class, 'store']) -> name('login');

// Rutas Protegidas
Route::middleware(['auth:sanctum'])->group(function () {

    // Usuarios
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'getUsers'])->name('getUsers');
        Route::get('/search', [UserController::class, 'search'])->name('users.search');
        Route::get('/active', [UserController::class, 'getUsers'])->name('users.active');
        Route::get('/inactive', [UserController::class, 'getInactiveUsers'])->name('users.inactive');
        Route::get('/constants', [UserController::class, 'getConstants'])->name('getConstants');
        Route::get('/{id}', [UserController::class, 'getUser'])->name('getUser'); 
        Route::post('/', [UserController::class, 'store'])->name('register.post');
        Route::post('/{userId}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update'); 
        Route::delete('/{id}', [UserController::class, 'delete'])->name('users.destroy');
    });

    //Roles
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'getRoles'])->name('getRols');
        Route::get('/modules', [RoleController::class, 'rols_index'])->name('rols.index');
        Route::get('/{id}', [RoleController::class, 'details'])->name('rols.details')      ;
        Route::post('/', [RoleController::class, 'store'])->name('rols.store');
        Route::put('/{id}', [RoleController::class, 'update'])->name('rols.update');
        Route::delete('/{id}', [RoleController::class, 'delete'])->name('rols.delete');
    });

    
    // Perfil
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('{id}/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::put('{id}/password/', [PasswordController::class, 'update'])->name('password.update');
    });
    

    //Imports de otros modulos 
    require __DIR__.'/HumanResource/employees.php';
    require __DIR__.'/Products/Products.php';
    require __DIR__.'/Warehouses/Warehouses.php';
    require __DIR__.'/Inventory/moves.php';
    require __DIR__.'/HumanResource/external-employees.php';

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
});

