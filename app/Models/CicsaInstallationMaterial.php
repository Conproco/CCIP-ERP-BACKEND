<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicsaInstallationMaterial extends Model
{
    use HasFactory;

    protected $table = 'cicsa_installation_materials';

    protected $fillable = [
        'name',
        'unit',
        'total_quantity',
        'quantity',
        'type',
        'used_quantity',
        'cicsa_installation_id'
    ];

    public function cicsa_installation ()
    {
        return $this->belongsTo(CicsaInstallation::class, 'cicsa_installation_id');
    }
}
