<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarDayRegister extends Model
{
    protected $fillable = [
        "register_date",
        "zone",
        "car_id",
        "km_start",
        "km_end",
        "km_start_image",
        "km_end_image",
        "fuel_supply",
        "user_id",
    ];

    protected $casts = [
        'fuel_supply' => 'array',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
