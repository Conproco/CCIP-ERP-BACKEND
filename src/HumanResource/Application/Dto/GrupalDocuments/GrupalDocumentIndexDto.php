<?php

namespace Src\HumanResource\Application\Dto\GrupalDocuments;

class GrupalDocumentIndexDto
{
    public function __construct(
        public readonly object $grupalDocuments,
        public readonly array $types
    ) {
    }

    public function toArray(): array
    {
        return [
            'grupal_documents' => $this->grupalDocuments,
            'types' => $this->types,
        ];
    }
}
