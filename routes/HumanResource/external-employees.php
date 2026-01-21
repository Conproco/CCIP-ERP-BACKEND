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
    Route::get('/', [ExternalEmployeesController::class, 'index'])->name('hr.external-employees.index');

    // Lista paginada de empleados externos con filtros
    Route::get('/list', [ExternalEmployeesController::class, 'getExternalEmployees'])->name('hr.external-employees.list');

    // CRUD
    Route::post('/', [ExternalEmployeesController::class, 'createExternalEmployee'])->name('hr.external-employees.store');
    Route::put('/{external_id}', [ExternalEmployeesController::class, 'updateExternalEmployee'])->name('hr.external-employees.update')->whereNumber('external_id');
    Route::delete('/{external_id}', [ExternalEmployeesController::class, 'deleteExternalEmployee'])->name('hr.external-employees.destroy')->whereNumber('external_id');

});
