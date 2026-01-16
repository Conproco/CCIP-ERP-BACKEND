<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BacklogSite extends Model
{
    use HasFactory;
    protected $fillable = [
        'site_id',
        'site_name',
        'site_region',
        'longitude',
        'latitude',
        'site_type_label',
        'site_priority',
        'access_type',
        'contratist',
        'energy_type',
        'room_type',
        'district',
        'address',
        'department',
        'province',
    ];
}
