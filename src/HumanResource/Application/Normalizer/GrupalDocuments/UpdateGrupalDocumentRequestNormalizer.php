<?php

namespace Src\HumanResource\Application\Normalizer\GrupalDocuments;

use App\Http\Requests\HumanResource\GrupalDocuments\UpdateGrupalDocumentRequest;
use Src\HumanResource\Application\Dto\GrupalDocuments\UpdateGrupalDocumentDto;

class UpdateGrupalDocumentRequestNormalizer
{
    public function normalize(UpdateGrupalDocumentRequest $request, int $id): UpdateGrupalDocumentDto
    {
        return new UpdateGrupalDocumentDto(
            id: $id,
            type: $request->get('type'),
            date: $request->get('date'),
            observation: $request->get('observation'),
            archive: $request->hasFile('archive') ? $request->file('archive') : null,
        );
    }
}
