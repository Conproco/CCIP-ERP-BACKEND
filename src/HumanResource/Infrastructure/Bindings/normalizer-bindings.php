<?php

// Bindings for Normalizers

use Src\HumanResource\Application\Normalizer\EmployeeListNormalizer;
use Src\HumanResource\Application\Normalizer\EmployeeListResponseNormalizer;
use Src\HumanResource\Application\Normalizer\EmployeeCreateNormalizer;

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
];
