<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers_contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'additional_information',
        'customers_cost_line_id'
    ];

    public function customers_cost_line()
    {
        return $this->belongsTo(CustomersCostLine::class, 'customers_cost_line_id');
    }
}
