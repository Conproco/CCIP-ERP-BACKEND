<?php

// Bindings for Normalizers

use Src\HumanResource\Application\Normalizer\EmployeeListNormalizer;
use Src\HumanResource\Application\Normalizer\EmployeeListResponseNormalizer;
// Agrega aquí otros normalizers si los tienes, por ejemplo:
// use Src\HumanResource\Application\Normalizer\ContractNormalizer;
// use Src\HumanResource\Application\Normalizer\EmployeeDataNormalizer;

return [
    EmployeeListNormalizer::class => [
        EmployeeListNormalizer::class,
    ],
    EmployeeListResponseNormalizer::class => [
        EmployeeListResponseNormalizer::class,
    ],
];
