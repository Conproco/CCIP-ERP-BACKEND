<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HumanResource\GrupalDocumentsController;

/*
|--------------------------------------------------------------------------
| Rutas de Documentos Grupales (Human Resource)
|--------------------------------------------------------------------------
| Prefijo automático definido en el ServiceProvider: 
| /api/human-resource/grupal-documents
*/

Route::prefix('human-resource/grupal-documents')->group(function () {
    // Index - Obtener documentos paginados y tipos
    Route::get('/', [GrupalDocumentsController::class, 'index'])->name('hr.grupal-documents.index');

    // CRUD
    Route::post('/', [GrupalDocumentsController::class, 'store'])->name('hr.grupal-documents.store');

    // PUT para JSON, POST para form-data con archivos (PHP no soporta PUT + multipart/form-data)
    Route::put('/{gd_id}', [GrupalDocumentsController::class, 'update'])->name('hr.grupal-documents.update')->whereNumber('gd_id');
    Route::post('/{gd_id}', [GrupalDocumentsController::class, 'update'])->name('hr.grupal-documents.update-post')->whereNumber('gd_id');

    Route::delete('/{gd_id}', [GrupalDocumentsController::class, 'destroy'])->name('hr.grupal-documents.destroy')->whereNumber('gd_id');

    // Download
    Route::get('/{gd_id}/download', [GrupalDocumentsController::class, 'download'])->name('hr.grupal-documents.download')->whereNumber('gd_id');
});

