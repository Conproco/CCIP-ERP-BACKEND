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

    
});
