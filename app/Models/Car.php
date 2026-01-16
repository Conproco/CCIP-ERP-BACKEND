<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'brand',
        'model',
        'plate',
        'year',
        'type',
        'photo',
        'user_id',
        'cost_line_id',
    ];

    //relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assigned_users()
    {
        return $this->belongsToMany(User::class, 'car_users');
    }

    public function costline()
    {
        return $this->belongsTo(CostLine::class, 'cost_line_id');
    }

    public function car_document()
    {
        return $this->hasOne(CarDocument::class, 'car_id');
    }

    public function car_changelogs()
    {
        return $this->hasMany(CarChangelog::class, 'car_id');
    }

    public function checklist()
    {
        return $this->hasMany(ChecklistCar::class, 'car_id');
    }

    public function car_day_registers()
    {
        return $this->hasMany(CarDayRegister::class, 'car_id');
    }

    public function getCarDayRegistersKeyByDateAttribute()
    {
        return (object) $this->car_day_registers()->get()->keyBy('register_date');
    }
}
