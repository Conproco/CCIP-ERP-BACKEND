<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistDailytoolkit extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_name',
        'personal_2',
        'zone',
        'power_meter',
        'ammeter_clamp',
        'cutting_pliers',
        'nose_pliers',
        'universal_pliers',
        'tape',
        'cutter',
        'knob_driver',
        'screwdriver_set',
        'allenkeys_set',
        'thor_screwboard',
        'helmet_flashlight',
        'freanch_key',
        'pyrometer',
        'laptop',
        'console_cables',
        'network_adapter',
        'observations'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');    
    }
}
