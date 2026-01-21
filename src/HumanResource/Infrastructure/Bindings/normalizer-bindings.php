<?php

// Bindings for Normalizers

// Employees Normalizers
use Src\HumanResource\Application\Normalizer\EmployeeListNormalizer;
use Src\HumanResource\Application\Normalizer\EmployeeListResponseNormalizer;
use Src\HumanResource\Application\Normalizer\EmployeeCreateNormalizer;
use Src\HumanResource\Application\Normalizer\StoreEmployeeRequestNormalizer;
use Src\HumanResource\Application\Normalizer\UpdateEmployeeRequestNormalizer;
use Src\HumanResource\Application\Normalizer\FireEmployeeRequestNormalizer;

// External Employees Normalizers
use Src\HumanResource\Application\Normalizer\ExternalEmployees\ExternalEmployeeIndexNormalizer;
use Src\HumanResource\Application\Normalizer\ExternalEmployees\StoreExternalEmployeeRequestNormalizer;
use Src\HumanResource\Application\Normalizer\ExternalEmployees\UpdateExternalEmployeeRequestNormalizer;

return [
        // Employees
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
    UpdateEmployeeRequestNormalizer::class => [
        UpdateEmployeeRequestNormalizer::class,
    ],
    FireEmployeeRequestNormalizer::class => [
        FireEmployeeRequestNormalizer::class,
    ],

        // External Employees
    ExternalEmployeeIndexNormalizer::class => [
        ExternalEmployeeIndexNormalizer::class,
    ],
    StoreExternalEmployeeRequestNormalizer::class => [
        StoreExternalEmployeeRequestNormalizer::class,
    ],
    UpdateExternalEmployeeRequestNormalizer::class => [
        UpdateExternalEmployeeRequestNormalizer::class,
    ],
];


