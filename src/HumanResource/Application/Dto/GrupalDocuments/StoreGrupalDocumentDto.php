<?php

namespace Src\HumanResource\Application\Dto\GrupalDocuments;

use Illuminate\Http\UploadedFile;

class StoreGrupalDocumentDto
{
    public function __construct(
        public readonly string $type,
        public readonly string $date,
        public readonly ?string $observation = null,
        public readonly ?UploadedFile $archive = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'date' => $this->date,
            'observation' => $this->observation,
        ];
    }
}
