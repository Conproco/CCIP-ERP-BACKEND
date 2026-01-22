<?php

namespace Src\HumanResource\Application\Normalizer\GrupalDocuments;

use Src\HumanResource\Application\Dto\GrupalDocuments\GrupalDocumentIndexDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GrupalDocumentIndexNormalizer
{
    /**
     * Normalize grupal documents and types to DTO
     */
    public function normalize(LengthAwarePaginator $grupalDocuments, array $types): GrupalDocumentIndexDto
    {
        return new GrupalDocumentIndexDto($grupalDocuments, $types);
    }

    public function supports($data): bool
    {
        return $data instanceof LengthAwarePaginator;
    }
}
