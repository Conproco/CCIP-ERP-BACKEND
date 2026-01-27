<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Persistence\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

/**
 * ActionLog Eloquent Model
 * Infrastructure layer representation of audit logs
 */
class ActionLog extends Model
{
    protected $fillable = [
        'table_name',
        'row_id',
        'action',
        'data',
        'user_id',
    ];

    protected $casts = [
        'data' => 'array',
        'row_id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * Relationship with User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Convert to Domain Entity
     */
    public function toDomain(): \Src\Shared\Domain\Entities\ActionLog
    {
        return new \Src\Shared\Domain\Entities\ActionLog(
            id: $this->id,
            tableName: $this->table_name,
            rowId: $this->row_id,
            action: $this->action,
            data: $this->data,
            userId: $this->user_id,
            createdAt: $this->created_at,
            updatedAt: $this->updated_at,
        );
    }
}
