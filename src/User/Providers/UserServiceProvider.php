<?php

namespace Src\User\Providers;

use Illuminate\Support\ServiceProvider;
use Src\User\Application\Services\UserService;
use Src\User\Domain\Repositories\UserRepository;
use Src\User\Domain\Rules\UserRules;
use Src\User\Infrastructure\Persistence\EloquentUserRepository;


class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $bindingsPath = base_path('src/User/Infrastructure/Bindings/repository-bindings.php');
        
        if (file_exists($bindingsPath)) {
            $bindings = require $bindingsPath;
            foreach ($bindings as $abstract => $concrete) {
                $this->app->bind($abstract, $concrete);
            }
        }

        $this->app->singleton(UserService::class, function ($app) {
            return new UserService(
                $app->make(UserRepository::class),
                $app->make(UserRules::class) // <--- AGREGA ESTA LÍNEA
            );
        });
    }

    public function boot(): void
    {
        
        $routesPath = base_path('src/User/Infrastructure/Routes/api.php');
        
        if (file_exists($routesPath)) {
            $this->loadRoutesFrom($routesPath);
        }
    }
}
