<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            
            Route::middleware('api')
                ->group(base_path('routes/api.php'));

            // Modular: HumanResource/Employees
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/HumanResource/employees.php'));

            // Modular: HumanResource/ExternalEmployees
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/HumanResource/external-employees.php'));

            // Modular: HumanResource/GrupalDocuments
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/HumanResource/grupal-documents.php'));
        });
    }
}


