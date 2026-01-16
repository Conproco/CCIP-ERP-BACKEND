<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruc',
        'business_name',
        'category',
        'address',
    ];

    //Relations
    public function customers_cost_line()
    {
        return $this->hasMany(CustomersCostLine::class);
    }
}
