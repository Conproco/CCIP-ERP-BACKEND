<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class Purchase_product extends Model
{
    use HasFactory;
    protected $table = "purchase_products";
    protected $fillable = [
        'name', 
        'unit',
        'description',
        'type',
        'type_product',
        'state',
        'resource_type_id'
    ];

    public $appends = [
        'code'
    ];

    //CALCULATED FIELDS
    public function getCodeAttribute()
    {
        if ($this->exists) {
            if ($this->type == 'Producto') {
                return 'PR' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
            } elseif ($this->type == 'Servicio') {
                return 'SE' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
            } elseif ($this->type == 'Activo') {
                return 'AC' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
            } else {
                return null;
            }
        } else {
            return 'TMP' . now()->format('ymdHis');
        }
    }

    //RELATIONS
    public function resource_type()
    {
        return $this->belongsTo(ResourceType::class,'resource_type_id');
    }
}

