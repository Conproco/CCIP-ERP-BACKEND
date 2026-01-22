<?php

namespace Src\HumanResource\Application\Normalizer\GrupalDocuments;

use App\Http\Requests\HumanResource\GrupalDocuments\StoreGrupalDocumentRequest;
use Src\HumanResource\Application\Dto\GrupalDocuments\StoreGrupalDocumentDto;

class StoreGrupalDocumentRequestNormalizer
{
    public function normalize(StoreGrupalDocumentRequest $request): StoreGrupalDocumentDto
    {
        return new StoreGrupalDocumentDto(
            type: $request->input('type'),
            date: $request->input('date'),
            observation: $request->input('observation'),
            archive: $request->hasFile('archive') ? $request->file('archive') : null,
        );
    }
}
