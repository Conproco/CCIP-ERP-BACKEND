<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Entities;

use DateTime;

/**
 * ActionLog Domain Entity
 * Represents an audit log entry for tracking changes to models
 */
final class ActionLog
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $tableName,
        public readonly int $rowId,
        public readonly string $action,
        public readonly array $data,
        public readonly ?int $userId,
        public readonly DateTime $createdAt,
        public readonly DateTime $updatedAt,
    ) {
    }

    public static function create(
        string $tableName,
        int $rowId,
        string $action,
        array $data,
        ?int $userId = null
    ): self {
        return new self(
            id: null,
            tableName: $tableName,
            rowId: $rowId,
            action: $action,
            data: $data,
            userId: $userId,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
        );
    }

    public function isCreated(): bool
    {
        return $this->action === 'created';
    }

    public function isUpdated(): bool
    {
        return $this->action === 'updated';
    }

    public function isDeleted(): bool
    {
        return $this->action === 'deleted';
    }
}
