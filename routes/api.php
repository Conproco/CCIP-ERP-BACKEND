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
    Route::middleware('permission')->group(function () {

        // Usuarios
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'getUsers'])->name('see_users_table');
            Route::get('/search', [UserController::class, 'search'])->name('see_users_table.search'); 
            Route::get('/active', [UserController::class, 'getUsers'])->name('see_users_table.active');
            Route::get('/inactive', [UserController::class, 'getInactiveUsers'])->name('see_users_table.inactive');
            Route::get('/{id}', [UserController::class, 'getUser'])->name('see_user'); 
            Route::post('/', [UserController::class, 'store'])->name('add_user');
            Route::post('/{userId}/restore', [UserController::class, 'restore'])->name('add_user.restore');
            Route::put('/{id}', [UserController::class, 'update'])->name('edit_user'); 
            Route::delete('/{id}', [UserController::class, 'delete'])->name('delete_user');
            Route::post('/{userId}/link', [UserController::class, 'linkEmployee'])->name('link_user');
        });

        //Roles
        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'getRoles'])->name('see_roles_table');
            Route::get('/modules', [RoleController::class, 'rols_index'])->name('see_roles_table.modules');
            Route::get('/{id}', [RoleController::class, 'details'])->name('see_role');
            Route::post('/', [RoleController::class, 'store'])->name('add_role');
            Route::put('/{id}', [RoleController::class, 'update'])->name('edit_role');
            Route::delete('/{id}', [RoleController::class, 'delete'])->name('delete_role');
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
        }
    );
});

