<?php

namespace Src\HumanResource\Providers;

use Illuminate\Support\ServiceProvider;
use Src\HumanResource\Application\Services\Employees\EmployeeQueryService;
use Src\HumanResource\Application\UseCases\Employees\StoreEmployeeUseCase;
use Src\HumanResource\Application\UseCases\Employees\UpdateEmployeeUseCase;
use Src\HumanResource\Application\UseCases\Employees\FireEmployeeUseCase;
use Src\HumanResource\Application\UseCases\Employees\ReentryEmployeeUseCase;
use Src\HumanResource\Application\UseCases\Employees\DeleteEmployeeUseCase;
use Src\HumanResource\Application\Normalizer\StoreEmployeeRequestNormalizer;
use Src\HumanResource\Application\Normalizer\UpdateEmployeeRequestNormalizer;
use Src\HumanResource\Application\Normalizer\FireEmployeeRequestNormalizer;
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

// External Employees imports
use Src\HumanResource\Application\Services\ExternalEmployees\ExternalEmployeesQueryService;
use Src\HumanResource\Application\Services\ExternalEmployees\ExternalEmployeesCommandService;
use Src\HumanResource\Application\Normalizer\ExternalEmployees\ExternalEmployeeIndexNormalizer;
use Src\HumanResource\Application\Normalizer\ExternalEmployees\StoreExternalEmployeeRequestNormalizer;
use Src\HumanResource\Application\Normalizer\ExternalEmployees\UpdateExternalEmployeeRequestNormalizer;
use Src\HumanResource\Domain\Ports\Repositories\ExternalEmployees\ExternalEmployeeRepositoryInterface;

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

        // Bind Normalizers
        $this->app->singleton(EmployeeCreateNormalizer::class);
        $this->app->singleton(StoreEmployeeRequestNormalizer::class);
        $this->app->singleton(UpdateEmployeeRequestNormalizer::class);
        $this->app->singleton(FireEmployeeRequestNormalizer::class);

        // External Employees Normalizers
        $this->app->singleton(ExternalEmployeeIndexNormalizer::class);
        $this->app->singleton(StoreExternalEmployeeRequestNormalizer::class);
        $this->app->singleton(UpdateExternalEmployeeRequestNormalizer::class);

        // Bind EmployeeQueryService with its dependencies
        $this->app->bind(EmployeeQueryService::class, function ($app) {
            return new EmployeeQueryService(
                $app->make(EmployeeRepositoryInterface::class),
                $app->make(CostLineRepositoryInterface::class),
                $app->make(EducationRepositoryInterface::class),
                $app->make(ContractRepositoryInterface::class),
                $app->make(\Src\HumanResource\Domain\Ports\Repositories\Employees\DocumentSectionRepositoryInterface::class),
                [
                    $app->make(\Src\HumanResource\Application\Normalizer\EmployeeListNormalizer::class),
                    $app->make(EmployeeListResponseNormalizer::class)
                ],
                $app->make(EmployeeCreateNormalizer::class),
                $app->make(FileStorageInterface::class)
            );
        });

        // Bind StoreEmployeeUseCase
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

        // Bind UpdateEmployeeUseCase
        $this->app->bind(UpdateEmployeeUseCase::class, function ($app) {
            return new UpdateEmployeeUseCase(
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

        // Bind FireEmployeeUseCase
        $this->app->bind(FireEmployeeUseCase::class, function ($app) {
            return new FireEmployeeUseCase(
                $app->make(EmployeeRepositoryInterface::class),
                $app->make(ContractRepositoryInterface::class),
                $app->make(\Src\HumanResource\Domain\Ports\Repositories\Employees\PayrollDetailRepositoryInterface::class),
                $app->make(FileStorageInterface::class)
            );
        });

        // Bind ReentryEmployeeUseCase
        $this->app->bind(ReentryEmployeeUseCase::class, function ($app) {
            return new ReentryEmployeeUseCase(
                $app->make(ContractRepositoryInterface::class)
            );
        });

        // Bind DeleteEmployeeUseCase
        $this->app->bind(DeleteEmployeeUseCase::class, function ($app) {
            return new DeleteEmployeeUseCase(
                $app->make(EmployeeRepositoryInterface::class)
            );
        });

        // Bind ExternalEmployeesQueryService
        $this->app->bind(ExternalEmployeesQueryService::class, function ($app) {
            return new ExternalEmployeesQueryService(
                $app->make(CostLineRepositoryInterface::class),
                $app->make(ExternalEmployeeRepositoryInterface::class),
                $app->make(ExternalEmployeeIndexNormalizer::class),
                $app->make(FileStorageInterface::class)
            );
        });

        // Bind ExternalEmployeesCommandService
        $this->app->bind(ExternalEmployeesCommandService::class, function ($app) {
            return new ExternalEmployeesCommandService(
                $app->make(ExternalEmployeeRepositoryInterface::class),
                $app->make(FileStorageInterface::class)
            );
        });

        // Bind GrupalDocumentsQueryService
        $this->app->bind(\Src\HumanResource\Application\Services\GrupalDocuments\GrupalDocumentsQueryService::class, function ($app) {
            return new \Src\HumanResource\Application\Services\GrupalDocuments\GrupalDocumentsQueryService(
                $app->make(\Src\HumanResource\Domain\Ports\Repositories\GrupalDocuments\GrupalDocumentRepositoryInterface::class),
                $app->make(\Src\HumanResource\Application\Normalizer\GrupalDocuments\GrupalDocumentIndexNormalizer::class)
            );
        });

        // Bind GrupalDocumentsCommandService
        $this->app->bind(\Src\HumanResource\Application\Services\GrupalDocuments\GrupalDocumentsCommandService::class, function ($app) {
            return new \Src\HumanResource\Application\Services\GrupalDocuments\GrupalDocumentsCommandService(
                $app->make(\Src\HumanResource\Domain\Ports\Repositories\GrupalDocuments\GrupalDocumentRepositoryInterface::class),
                $app->make(FileStorageInterface::class)
            );
        });

        // GrupalDocuments Normalizers
        $this->app->singleton(\Src\HumanResource\Application\Normalizer\GrupalDocuments\GrupalDocumentIndexNormalizer::class);
        $this->app->singleton(\Src\HumanResource\Application\Normalizer\GrupalDocuments\StoreGrupalDocumentRequestNormalizer::class);
        $this->app->singleton(\Src\HumanResource\Application\Normalizer\GrupalDocuments\UpdateGrupalDocumentRequestNormalizer::class);
    }

    public function boot(): void
    {
        // Register routes, migrations, etc. if needed
    }
}



