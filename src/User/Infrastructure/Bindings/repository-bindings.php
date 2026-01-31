<?php

use Src\User\Domain\Repositories\UserRepository;
use Src\User\Infrastructure\Persistence\EloquentUserRepository;

return [
    UserRepository::class => EloquentUserRepository::class,
];
