<?php

// Bindings for Normalizers

use Src\HumanResource\Application\Normalizer\EmployeeListNormalizer;
use Src\HumanResource\Application\Normalizer\EmployeeListResponseNormalizer;
use Src\HumanResource\Application\Normalizer\EmployeeCreateNormalizer;
use Src\HumanResource\Application\Normalizer\StoreEmployeeRequestNormalizer;

return [
    EmployeeListNormalizer::class => [
        EmployeeListNormalizer::class,
    ],
    EmployeeListResponseNormalizer::class => [
        EmployeeListResponseNormalizer::class,
    ],
    EmployeeCreateNormalizer::class => [
        EmployeeCreateNormalizer::class,
    ],
    StoreEmployeeRequestNormalizer::class => [
        StoreEmployeeRequestNormalizer::class,
    ],
];
