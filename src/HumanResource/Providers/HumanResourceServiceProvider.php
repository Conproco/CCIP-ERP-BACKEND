<?php

namespace Src\HumanResource\Providers;

use Illuminate\Support\ServiceProvider;
use Src\HumanResource\Application\Services\Employees\EmployeeQueryService;
use Src\HumanResource\Domain\Ports\Repositories\Employees\EmployeeRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\CostLineRepositoryInterface;
use Src\HumanResource\Application\Normalizer\EmployeeListResponseNormalizer;

class HumanResourceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Normalizer bindings
        $normalizerBindings = require base_path('src/HumanResource/Infrastructure/Bindings/normalizer-bindings.php');
        foreach ($normalizerBindings as $tag => $normalizers) {
            $this->app->tag($normalizers, $tag);
        }

        // Repository bindings
        $bindings = require base_path('src/HumanResource/Infrastructure/Bindings/repository-bindings.php');
        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }

        // Bind EmployeeQueryService with its dependencies
        $this->app->bind(EmployeeQueryService::class, function ($app) {
            return new EmployeeQueryService(
                $app->make(EmployeeRepositoryInterface::class),
                $app->make(CostLineRepositoryInterface::class),
                [
                    $app->make(\Src\HumanResource\Application\Normalizer\EmployeeListNormalizer::class),
                    $app->make(EmployeeListResponseNormalizer::class)
                ]
            );
        });
    }

    public function boot(): void
    {
        // Register routes, migrations, etc. if needed
    }
}
