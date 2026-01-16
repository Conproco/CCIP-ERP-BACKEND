<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'cost_line_id',
        'name',
        'code',
        'address',
        'principal_location_id'
    ];

    public function principal_location () {
        return $this->belongsTo(Location::class, 'principal_location_id');
    }
    
}

