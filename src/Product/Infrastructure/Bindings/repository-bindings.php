<?php

use Src\Product\Domain\Repositories\ProductRepository;
use Src\Product\Infrastructure\Persistence\EloquentProductRepository;
use Illuminate\Support\Facades\App;

App::bind(ProductRepository::class, EloquentProductRepository::class);
