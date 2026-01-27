<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Persistence\Traits;

use Illuminate\Support\Facades\Auth;
use Src\Shared\Infrastructure\Persistence\Models\ActionLog;

/**
 * AuditableTrait
 * 
 * Automatically logs create, update, and delete actions on Eloquent models
 * This trait should be used in Infrastructure layer (Eloquent models)
 */
trait AuditableTrait
{
    /**
     * Boot the auditable trait for a model
     */
    public static function bootAuditableTrait(): void
    {
        static::created(function ($model) {
            self::logAction('created', $model);
        });

        static::updated(function ($model) {
            self::logAction('updated', $model);
        });

        static::deleted(function ($model) {
            self::logAction('deleted', $model);
        });
    }

    /**
     * Log the action performed on the model
     */
    protected static function logAction(string $action, $model): void
    {
        $user = Auth::user();
        $data = $model->toArray();

        // For updates, store both before and after states
        if ($action === 'updated') {
            $data = [
                'before' => $model->getOriginal(),
                'after' => $model->getAttributes(),
            ];
        }

        ActionLog::create([
            'table_name' => $model->getTable(),
            'row_id' => $model->id,
            'action' => $action,
            'data' => $data,
            'user_id' => $user?->id,
        ]);
    }
}
