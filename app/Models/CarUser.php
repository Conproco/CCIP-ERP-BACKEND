<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarUser extends Model
{
    protected $fillable = [
        'car_id',
        'user_id',
    ];
}
