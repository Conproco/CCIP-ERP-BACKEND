<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Constants\Inventory\Location as LocationConstants;
use App\Services\Inventory\LocationServices;
use Illuminate\Validation\ValidationException;

class Location extends Model
{
    protected $fillable = [
        "name",
        "description",
        "type",
        "parent_id",
        "partner_id",
        "related_model",
    ];

    protected $appends = [
        'parent_name'
    ];

    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function getParentNameAttribute () {
        return $this->parent()->first()?->name;
    }
    // public function getDisplayNameAttribute () {
    //     return $this->parent()->first()->name;
    // }


    public static function getFlatHierarchy($rootId = null): Collection
    {
        $all = static::all()->keyBy('id');
        $roots = $rootId
            ? collect([$all[$rootId]])
            : $all->whereNull('parent_id');

        $result = collect();
        foreach ($roots as $root) {
            $result = $result->merge(static::flattenHierarchy($root, $all));
        }
        if ($rootId) {
            $result = $result->reject(fn($location) => $location->id == $rootId);
        }
        return $result->values();
    }

    public static function getWithoutAncestor($ancestorId)
    {
        $all = static::all()->keyBy('id');
        $excluded = static::getFlatHierarchy($ancestorId)->pluck('id')->toArray();
        return $all->whereNotIn('id', $excluded)->values();
    }

    private static function flattenHierarchy($location, $all): Collection
    {
        $result = collect([$location]);
        $children = $all->where('parent_id', $location->id);
        foreach ($children as $child) {
            $result = $result->merge(static::flattenHierarchy($child, $all));
        }
        return $result;
    }

    private static function sanitizeAndValidate($model): void
    {
        $model->name = trim($model->name ?? '');
        $model->description = trim($model->description ?? '');

        if (!empty($model->parent_id)) {
            $parent = Location::find($model->parent_id);

            if (!$parent) {
                throw ValidationException::withMessages([
                    'parent_id' => 'El padre especificado no existe.',
                ]);
            }
            if ($parent->type !== $model->type) {
                throw ValidationException::withMessages([
                    'type' => "El tipo de la ubicación hija ('{$model->type}') debe coincidir con el tipo del padre ('{$parent->type}').",
                ]);
            }
        }
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            self::sanitizeAndValidate($model);
        });
    }


    
}
