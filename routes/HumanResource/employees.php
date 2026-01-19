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
    Route::get('/', [ManagementEmployees::class, 'index'])->name('hr.employees.index');
    Route::get('/search', [ManagementEmployees::class, 'search'])->name('hr.employees.search');
});