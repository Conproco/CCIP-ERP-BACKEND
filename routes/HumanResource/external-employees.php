<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HumanResource\ExternalEmployeesController;

/*
|--------------------------------------------------------------------------
| Rutas de Empleados Externos (Human Resource)
|--------------------------------------------------------------------------
| Prefijo automático definido en el ServiceProvider: 
| /api/human-resource/external-employees
*/

Route::prefix('human-resource/external-employees')->group(function () {
    // Index - Obtener datos iniciales (líneas de costo)
    Route::get('/', [ExternalEmployeesController::class, 'index'])->name('employees.external.index');

    // Lista paginada de empleados externos con filtros
    Route::get('/list', [ExternalEmployeesController::class, 'getExternalEmployees'])->name('employees.external.getExternalEmployee');

    // CRUD
    Route::post('/', [ExternalEmployeesController::class, 'createExternalEmployee'])->name('employees.external.create');
    Route::put('/{external_id}', [ExternalEmployeesController::class, 'updateExternalEmployee'])->name('employees.external.update')->whereNumber('external_id');
    Route::delete('/{external_id}', [ExternalEmployeesController::class, 'deleteExternalEmployee'])->name('employees.external.delete')->whereNumber('external_id');

    // Archivos
    Route::get('/{id}/profile-image', [ExternalEmployeesController::class, 'showExternalProfileImage'])->name('management.external.employee.profile.show')->whereNumber('id');
    Route::get('/{id}/curriculum-vitae', [ExternalEmployeesController::class, 'previewCurriculumVitae'])->name('employees.external.preview.curriculum_vitae')->whereNumber('id');
    
});
