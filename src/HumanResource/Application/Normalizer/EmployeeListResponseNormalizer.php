<?php
namespace Src\HumanResource\Application\Normalizer;

use Src\HumanResource\Application\Dto\EmployeeListResponseDto;

class EmployeeListResponseNormalizer
{
    public function supports($data): bool
    {
        return $data instanceof EmployeeListResponseDto;
    }

    public function normalize(EmployeeListResponseDto $dto): array
    {
        return $dto->toArray();
    }
}
