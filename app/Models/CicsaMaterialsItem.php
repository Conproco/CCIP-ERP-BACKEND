<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicsaMaterialsItem extends Model
{
    use HasFactory;
    protected $table = 'cicsa_materials_items';

    protected $fillable = [
        'cicsa_material_id',
        'code_ax',
        'name',
        'unit',
        'type',
        'quantity',
        'total_quantity',
    ];

    public function cicsa_material() {
        return $this->belongsTo(CicsaMaterial::class, 'cicsa_material_id');
    }
}
