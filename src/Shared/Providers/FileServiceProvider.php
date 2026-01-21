<?php

namespace Src\Shared\Providers;

use Src\Shared\Application\Interfaces\FileStorageInterface;
use Src\Shared\Infrastructure\Adapters\LaravelStorageAdapter;
use Illuminate\Support\ServiceProvider;

class FileServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FileStorageInterface::class, LaravelStorageAdapter::class);
    }

    public function boot(): void
    {
        //
    }
}
