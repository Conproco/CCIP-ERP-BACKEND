<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomersCostLine extends Model
{
    protected $fillable = [
        'name',
        'area_manager',
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function customer_contacts()
    {
        return $this->hasMany(Customers_contact::class);
    }
}
