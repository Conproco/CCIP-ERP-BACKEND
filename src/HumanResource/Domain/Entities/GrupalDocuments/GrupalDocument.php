<?php

namespace Src\HumanResource\Domain\Entities\GrupalDocuments;

class GrupalDocument
{
    public function __construct(
        private ?int $id = null,
        private ?string $type = null,
        private ?string $archive = null,
        private ?string $date = null,
        private ?string $observation = null,
        private ?string $createdAt = null,
        private ?string $updatedAt = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getArchive(): ?string
    {
        return $this->archive;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'archive' => $this->archive,
            'date' => $this->date,
            'observation' => $this->observation,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
