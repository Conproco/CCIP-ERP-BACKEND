<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HumanResource\ManagementEmployees;

/*
|--------------------------------------------------------------------------
| Rutas de Empleados (Human Resource)
|--------------------------------------------------------------------------
| Prefijo automático definido en el ServiceProvider: 
| /api/human-resource/employees
*/

Route::prefix('human-resource/employees')->group(function () {
    // Listar y buscar
    Route::get('/', [ManagementEmployees::class, 'index'])->name('hr.employees.index');
    Route::get('/search', [ManagementEmployees::class, 'search'])->name('hr.employees.search');

    // Formularios
    Route::get('/information_additional', [ManagementEmployees::class, 'create'])->name('hr.employees.create');

    // CRUD
    Route::post('/', [ManagementEmployees::class, 'store'])->name('hr.employees.store');
    Route::get('/{id}', [ManagementEmployees::class, 'edit'])->name('hr.employees.edit')->whereNumber('id');
    Route::put('/{id}', [ManagementEmployees::class, 'update'])->name('hr.employees.update')->whereNumber('id');
    Route::delete('/{id}', [ManagementEmployees::class, 'destroy'])->name('hr.employees.destroy')->whereNumber('id');

    
});