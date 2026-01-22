<?php

namespace Src\HumanResource\Application\Services\GrupalDocuments;

use Src\HumanResource\Domain\Ports\Repositories\GrupalDocuments\GrupalDocumentRepositoryInterface;
use Src\HumanResource\Application\Dto\GrupalDocuments\StoreGrupalDocumentDto;
use Src\HumanResource\Application\Dto\GrupalDocuments\UpdateGrupalDocumentDto;
use Src\Shared\Application\Interfaces\FileStorageInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GrupalDocumentsCommandService
{
    private const DOCUMENTS_PATH = 'documents/documents/grupal/';

    public function __construct(
        private GrupalDocumentRepositoryInterface $grupalDocumentRepository,
        private FileStorageInterface $fileStorage
    ) {
    }

    /**
     * Store a new grupal document
     */
    public function store(StoreGrupalDocumentDto $dto): object
    {
        $data = $dto->toArray();

        // Handle archive file upload
        if ($dto->archive) {
            $filename = $this->fileStorage->generateFilename(
                'documentos_grupales',
                '',
                $dto->archive->getClientOriginalExtension()
            );
            $this->fileStorage->store($dto->archive, self::DOCUMENTS_PATH, $filename);
            $data['archive'] = $filename;
        }

        return $this->grupalDocumentRepository->create($data);
    }

    /**
     * Update an existing grupal document
     */
    public function update(UpdateGrupalDocumentDto $dto): object
    {
        $data = $dto->toArray();

        // Handle archive file upload - delete old file if replacing
        if ($dto->archive) {
            // Get existing document to delete old file
            $existingDocument = $this->grupalDocumentRepository->find($dto->id);

            // Delete old file if exists
            if ($existingDocument && $existingDocument->getArchive()) {
                $this->fileStorage->delete(self::DOCUMENTS_PATH . $existingDocument->getArchive());
            }

            // Store new file
            $filename = $this->fileStorage->generateFilename(
                'documentos_grupales',
                '',
                $dto->archive->getClientOriginalExtension()
            );
            $this->fileStorage->store($dto->archive, self::DOCUMENTS_PATH, $filename);
            $data['archive'] = $filename;
        }

        return $this->grupalDocumentRepository->update($dto->id, $data);
    }

    /**
     * Delete a grupal document
     */
    public function delete(int $id): bool
    {
        // Delete file before deleting record
        $document = $this->grupalDocumentRepository->find($id);
        if ($document && $document->getArchive()) {
            $this->fileStorage->delete(self::DOCUMENTS_PATH . $document->getArchive());
        }

        return $this->grupalDocumentRepository->delete($id);
    }

    /**
     * Download a grupal document
     */
    public function download(int $id): BinaryFileResponse
    {
        $document = $this->grupalDocumentRepository->find($id);

        if (!$document || !$document->getArchive()) {
            abort(404, 'Documento no encontrado');
        }

        return $this->fileStorage->download(
            self::DOCUMENTS_PATH . $document->getArchive(),
            $document->getArchive()
        );
    }
}

