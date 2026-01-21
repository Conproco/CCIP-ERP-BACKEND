<?php

namespace Src\Units\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Units\Domain\Repositories\UnitRepository;
use Src\Units\Infrastructure\Persistence\EloquentUnitRepository;
use Src\Units\Application\Services\UnitService;
use Src\Product\Domain\Repositories\ProductRepository;

class UnitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UnitRepository::class, EloquentUnitRepository::class);

        $this->app->singleton(UnitService::class, function ($app) {
            return new UnitService(
                $app->make(UnitRepository::class),
                $app->make(ProductRepository::class)
            );
        });
    }
}