<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarChangelog extends Model
{
    use HasFactory;

    protected $table = 'car_changelogs';

    protected $fillable = [
        'date',
        'mileage',
        'type',
        'invoice',
        'workshop',
        'contact_name',
        'contact_phone',
        'observation',
        'is_accepted',
        'car_id',
    ];

    //relations
    public function car() {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function car_changelog_items() {
        return $this->hasMany(CarChangelogItem::class, 'car_changelog_id');
    }
}
