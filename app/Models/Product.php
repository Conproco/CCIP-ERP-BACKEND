<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'primary_code',
        'secondary_code',
        'sc_type',
        'unit_id'
    ];

    protected $appends = ['unit_name'];

    public function existences() {
        return $this->hasMany(Existence::class, 'product_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function getUnitNameAttribute()
    {
        return $this->unit?->name ?? 'Sin unidad';
    }
}
