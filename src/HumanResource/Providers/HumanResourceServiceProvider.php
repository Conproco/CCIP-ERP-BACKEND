<?php

namespace Src\HumanResource\Providers;

use Illuminate\Support\ServiceProvider;
use Src\HumanResource\Application\Services\Employees\EmployeeQueryService;
use Src\HumanResource\Application\UseCases\Employees\StoreEmployeeUseCase;
use Src\HumanResource\Application\Normalizer\StoreEmployeeRequestNormalizer;
use Src\HumanResource\Domain\Ports\Repositories\Employees\EmployeeRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\ContractRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\EducationRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\AddressRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\EmergencyContactRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\FamilyDependentRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\HealthRepositoryInterface;
use Src\HumanResource\Domain\Ports\Repositories\Employees\CostLineRepositoryInterface;
use Src\HumanResource\Application\Normalizer\EmployeeListResponseNormalizer;
use Src\HumanResource\Application\Normalizer\EmployeeCreateNormalizer;
use Src\Shared\Application\Interfaces\FileStorageInterface;

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

        // Bind EmployeeCreateNormalizer
        $this->app->singleton(EmployeeCreateNormalizer::class);

        // Bind StoreEmployeeRequestNormalizer
        $this->app->singleton(StoreEmployeeRequestNormalizer::class);

        // Bind EmployeeQueryService with its dependencies
        $this->app->bind(EmployeeQueryService::class, function ($app) {
            return new EmployeeQueryService(
                $app->make(EmployeeRepositoryInterface::class),
                $app->make(CostLineRepositoryInterface::class),
                [
                    $app->make(\Src\HumanResource\Application\Normalizer\EmployeeListNormalizer::class),
                    $app->make(EmployeeListResponseNormalizer::class)
                ],
                $app->make(EmployeeCreateNormalizer::class)
            );
        });

        // Bind StoreEmployeeUseCase with all its dependencies
        $this->app->bind(StoreEmployeeUseCase::class, function ($app) {
            return new StoreEmployeeUseCase(
                $app->make(EmployeeRepositoryInterface::class),
                $app->make(ContractRepositoryInterface::class),
                $app->make(EducationRepositoryInterface::class),
                $app->make(AddressRepositoryInterface::class),
                $app->make(EmergencyContactRepositoryInterface::class),
                $app->make(FamilyDependentRepositoryInterface::class),
                $app->make(HealthRepositoryInterface::class),
                $app->make(FileStorageInterface::class)
            );
        });
    }

    public function boot(): void
    {
        // Register routes, migrations, etc. if needed
    }
}

