<?php

namespace Src\HumanResource\Application\Normalizer\ExternalEmployees;

use Src\HumanResource\Application\Dto\ExternalEmployees\ExternalEmployeeIndexDto;
use Illuminate\Database\Eloquent\Collection;

class ExternalEmployeeIndexNormalizer
{
    /**
     * Normalize cost lines collection to DTO
     */
    public function normalize(Collection $costLines): ExternalEmployeeIndexDto
    {
        $normalizedCostLines = [];

        foreach ($costLines as $costLine) {
            $normalizedCostLines[] = [
                'id' => $costLine->id,
                'name' => $costLine->name,
            ];
        }

        return new ExternalEmployeeIndexDto($normalizedCostLines);
    }

    public function supports($data): bool
    {
        return $data instanceof Collection;
    }
}
