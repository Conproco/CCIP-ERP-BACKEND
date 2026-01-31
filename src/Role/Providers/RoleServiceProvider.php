<?php

namespace Src\Role\Providers;

use Src\Role\Domain\Repositories\RoleRepository;
use Src\Role\Infrastructure\Persistence\EloquentRoleRepository;
use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        $this->app->bind(RoleRepository::class, EloquentRoleRepository::class);
    }

}
