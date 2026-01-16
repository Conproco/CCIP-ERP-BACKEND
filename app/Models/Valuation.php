<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Valuation extends Model
{
    protected $fillable = [
        'product_id',
        'total_quantity',
        'unit_cost',
        'move_id',
        'used_quantity',
    ];
}
