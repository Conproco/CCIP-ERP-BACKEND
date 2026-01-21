<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Inventory\ProductController;

Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'getProducts']);
        Route::get('/constants', [ProductController::class, 'index']); // Para obtener unidades, etc.
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'disable']);
        Route::post('/restore/{id}', [ProductController::class, 'restore']);
        Route::get('/search', [ProductController::class, 'search']);
    });