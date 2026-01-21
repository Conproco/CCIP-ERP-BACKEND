<?php

namespace Src\Product\Providers;

use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar bindings de repositorios
        require_once __DIR__ . '/../Infrastructure/Bindings/repository-bindings.php';
    }

}
