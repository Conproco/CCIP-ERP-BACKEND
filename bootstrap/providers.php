<?php

return [
    App\Providers\AppServiceProvider::class,
    //App\Providers\AuthServiceProvider::class,
    //App\Providers\EventServiceProvider::class,
    //App\Providers\RouteServiceProvider::class,
    App\Providers\ScrambleServiceProvider::class,
    
    // Módulos Hexagonales (Asegúrate de que existan)
    Src\User\Providers\UserServiceProvider::class,
    Src\Role\Providers\RoleServiceProvider::class,
    Src\Product\Providers\ProductServiceProvider::class,
    Src\Units\Providers\UnitServiceProvider::class,
];
