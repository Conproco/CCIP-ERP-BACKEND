<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HumanResource\ManagementEmployees;

/*
|--------------------------------------------------------------------------
| Rutas de Empleados (Human Resource)
|--------------------------------------------------------------------------
| Prefijo automático definido en el ServiceProvider: 
| /api/human-resource/employees
| Names compatibles con sistema legacy para permisos
*/

Route::prefix('human-resource/employees')->group(function () {
    // Listar y buscar
    Route::get('/', [ManagementEmployees::class, 'index'])->name('management.employees');
    Route::get('/search', [ManagementEmployees::class, 'search'])->name('management.employees.search');
    Route::get('/active', [ManagementEmployees::class, 'searchActive'])->name('management.employees.active');
    Route::get('/inactive', [ManagementEmployees::class, 'searchInactive'])->name('management.employees.inactive');

    // Formularios
    Route::get('/information_additional', [ManagementEmployees::class, 'create'])->name('management.employees.create');

    // CRUD
    Route::post('/', [ManagementEmployees::class, 'store'])->name('management.employees.store');
    Route::get('/{id}', [ManagementEmployees::class, 'edit'])->name('management.employees.edit')->whereNumber('id');
    Route::put('/{id}', [ManagementEmployees::class, 'update'])->name('management.employees.update')->whereNumber('id');
    Route::delete('/{id}', [ManagementEmployees::class, 'destroy'])->name('management.employees.destroy')->whereNumber('id');

    // Detalles y acciones
    Route::get('/{id}/details', [ManagementEmployees::class, 'details'])->name('management.employees.show')->whereNumber('id');
    Route::post('/{id}/fire', [ManagementEmployees::class, 'fired'])->name('management.employees.fired')->whereNumber('id');
    Route::post('/{id}/reentry', [ManagementEmployees::class, 'reentry'])->name('management.employees.reentry')->whereNumber('id');

    // Archivos
    Route::get('/{id}/profile-image', [ManagementEmployees::class, 'showProfileImage'])->name('management.employee.profile.show')->whereNumber('id');
    Route::get('/education/{id}/download-cv', [ManagementEmployees::class, 'downloadCv'])->name('management.employees.information.details.download')->whereNumber('id');
    Route::get('/contract/{id}/discharge-document', [ManagementEmployees::class, 'showDischargeDocument'])->name('management.employees.discharge.document')->whereNumber('id');

    // Cumpleaños
    Route::get('/happy-birthday', [ManagementEmployees::class, 'happyBirthday'])->name('management.employees.happy.birthday');

    // Constantes de empleados activos para payroll
    Route::get('/active-constants', [ManagementEmployees::class, 'getActiveEmployeesConstant'])->name('management.employees.active.constants');
});