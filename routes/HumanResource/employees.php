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

    // Detalles y acciones
    Route::get('/{id}/details', [ManagementEmployees::class, 'details'])->name('hr.employees.details')->whereNumber('id');
    Route::post('/{id}/fire', [ManagementEmployees::class, 'fired'])->name('hr.employees.fire')->whereNumber('id');
    Route::post('/{id}/reentry', [ManagementEmployees::class, 'reentry'])->name('hr.employees.reentry')->whereNumber('id');

    // Archivos
    Route::get('/{id}/profile-image', [ManagementEmployees::class, 'showProfileImage'])->name('hr.employees.profile-image')->whereNumber('id');
    Route::get('/education/{id}/download-cv', [ManagementEmployees::class, 'downloadCv'])->name('hr.employees.download-cv')->whereNumber('id');
    Route::get('/contract/{id}/discharge-document', [ManagementEmployees::class, 'showDischargeDocument'])->name('hr.employees.discharge-document')->whereNumber('id');

    // Cumpleaños
    Route::get('/happy-birthday', [ManagementEmployees::class, 'happyBirthday'])->name('hr.employees.happy-birthday');

});