<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ScrambleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Configurar el esquema de seguridad Bearer para Sanctum
        Scramble::extendOpenApi(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer', 'sanctum')
            );
        });

        // Filtrar solo rutas que comiencen con 'api/'
        Scramble::routes(function (Route $route) {
            // Solo documentar rutas que empiecen con 'api/'
            // y que estén definidas en routes/api.php
            return Str::startsWith($route->uri, 'api/') 
                && !Str::contains($route->uri, ['sanctum', 'ignition']);
        });

        // Ignorar rutas de autenticación web
        Scramble::ignoreDefaultRoutes();
    }
}
