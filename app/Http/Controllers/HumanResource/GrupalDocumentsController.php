<?php

namespace App\Http\Controllers\HumanResource;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\HumanResource\GrupalDocuments\StoreGrupalDocumentRequest;
use App\Http\Requests\HumanResource\GrupalDocuments\UpdateGrupalDocumentRequest;
use Src\HumanResource\Application\Services\GrupalDocuments\GrupalDocumentsQueryService;
use Src\HumanResource\Application\Services\GrupalDocuments\GrupalDocumentsCommandService;
use Src\HumanResource\Application\Normalizer\GrupalDocuments\StoreGrupalDocumentRequestNormalizer;
use Src\HumanResource\Application\Normalizer\GrupalDocuments\UpdateGrupalDocumentRequestNormalizer;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GrupalDocumentsController extends Controller
{
    public function __construct(
        protected GrupalDocumentsQueryService $queryService,
        protected GrupalDocumentsCommandService $commandService,
        protected StoreGrupalDocumentRequestNormalizer $storeNormalizer,
        protected UpdateGrupalDocumentRequestNormalizer $updateNormalizer
    ) {
    }

    /**
     * GET /api/human-resource/grupal-documents
     * Index - returns paginated documents and types
     */
    public function index(): JsonResponse
    {
        $response = $this->queryService->getIndexData();
        return response()->json($response->toArray(), 200);
    }

    /**
     * POST /api/human-resource/grupal-documents
     * Store a new grupal document
     */
    public function store(StoreGrupalDocumentRequest $request): JsonResponse
    {
        $dto = $this->storeNormalizer->normalize($request);
        $document = $this->commandService->store($dto);
        return response()->json($document, 201);
    }

    /**
     * PUT /api/human-resource/grupal-documents/{gd_id}
     * Update an existing grupal document
     */
    public function update(UpdateGrupalDocumentRequest $request, int $gd_id): JsonResponse
    {
        $dto = $this->updateNormalizer->normalize($request, $gd_id);
        $document = $this->commandService->update($dto);
        return response()->json($document, 200);
    }

    /**
     * DELETE /api/human-resource/grupal-documents/{gd_id}
     * Delete a grupal document
     */
    public function destroy(int $gd_id): JsonResponse
    {
        $this->commandService->delete($gd_id);
        return response()->json(['message' => 'Documento eliminado exitosamente'], 200);
    }

    /**
     * GET /api/human-resource/grupal-documents/{gd_id}/download
     * Download a grupal document
     */
    public function download(int $gd_id): BinaryFileResponse
    {
        return $this->commandService->download($gd_id);
    }
}
