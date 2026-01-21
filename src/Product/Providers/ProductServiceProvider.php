<?php

namespace Src\Product\Providers;

use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        require_once __DIR__ . '/../Infrastructure/Bindings/repository-bindings.php';
    }

}
