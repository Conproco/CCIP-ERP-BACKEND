<?php

namespace Src\HumanResource\Application\Normalizer\Payroll;

use App\Http\Requests\HumanResource\Payroll\GetAmountMassiveRequest;
use Src\HumanResource\Application\Dto\Payroll\GetAmountMassiveDto;

class GetAmountMassiveRequestNormalizer
{
    public function normalize(GetAmountMassiveRequest $request): GetAmountMassiveDto
    {
        $validated = $request->validated();

        return new GetAmountMassiveDto(
            ids: array_map('intval', $validated['ids']),
            type: $validated['type']
        );
    }
}
