<?php

return [
    App\Providers\AppServiceProvider::class,
    //App\Providers\AuthServiceProvider::class,
    //App\Providers\EventServiceProvider::class,
    //App\Providers\RouteServiceProvider::class,
    App\Providers\ScrambleServiceProvider::class,
    
    // Módulos Hexagonales 
    Src\User\Providers\UserServiceProvider::class,
    Src\Role\Providers\RoleServiceProvider::class,
    Src\Product\Providers\ProductServiceProvider::class,
    Src\Units\Providers\UnitServiceProvider::class,
    Src\Inventory\Providers\WarehouseServiceProvider::class,
    Src\HumanResource\Providers\HumanResourceServiceProvider::class,
    Src\Shared\Providers\FileServiceProvider::class,
];
