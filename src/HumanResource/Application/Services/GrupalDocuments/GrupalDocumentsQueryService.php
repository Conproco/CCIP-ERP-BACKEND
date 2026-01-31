<?php

namespace Src\HumanResource\Application\Services\GrupalDocuments;

use Src\HumanResource\Domain\Ports\Repositories\GrupalDocuments\GrupalDocumentRepositoryInterface;
use Src\HumanResource\Application\Normalizer\GrupalDocuments\GrupalDocumentIndexNormalizer;
use Src\HumanResource\Application\Dto\GrupalDocuments\GrupalDocumentIndexDto;
use Src\Shared\Domain\Constants\DocumentConstants;

class GrupalDocumentsQueryService
{
    public function __construct(
        private GrupalDocumentRepositoryInterface $grupalDocumentRepository,
        private GrupalDocumentIndexNormalizer $indexNormalizer
    ) {
    }

    /**
     * Get index data for grupal documents view
     * Returns paginated documents and types
     */
    public function getIndexData(int $perPage = 20): GrupalDocumentIndexDto
    {
        $grupalDocuments = $this->grupalDocumentRepository->getAllPaginated($perPage);
        $types = DocumentConstants::grupalDocuments();

        return $this->indexNormalizer->normalize($grupalDocuments, $types);
    }
}
