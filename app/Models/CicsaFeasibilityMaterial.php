<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicsaFeasibilityMaterial extends Model
{
    use HasFactory;

    protected $table = 'cicsa_feasibility_materials';

    protected $fillable = [
        'code_ax',
        'name',
        'unit',
        'quantity',
        'cicsa_feasibility_id'
    ];

    public function cicsa_feasibility ()
    {
        return $this->belongsTo(CicsaFeasibility::class, 'cicsa_feasibility_id');
    }
}
