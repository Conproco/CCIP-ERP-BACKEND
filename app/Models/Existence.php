<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Existence extends Model
{
    protected $fillable = [
        'product_id',
        'location_id',
        'unit_id',
        'quantity',
        'serial_number'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function location() {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function unit() {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    
}
