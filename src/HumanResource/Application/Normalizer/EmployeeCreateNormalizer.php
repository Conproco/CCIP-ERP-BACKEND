<?php

namespace Src\HumanResource\Application\Normalizer;

use Src\HumanResource\Application\Dto\EmployeeCreateResponse;

class EmployeeCreateNormalizer
{
    public function normalize(EmployeeCreateResponse $dto): array
    {
        return $dto->toArray();
    }
}
