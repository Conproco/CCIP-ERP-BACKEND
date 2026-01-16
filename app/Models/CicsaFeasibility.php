<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CicsaFeasibility extends Model
{
    use HasFactory;

    protected $table = 'cicsa_feasibilities';

    protected $fillable = [
        'feasibility_date',
        'report',
        'coordinator',
        'user_name',
        'user_id',
        'cicsa_assignation_id'
    ];

    public function user ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cicsa_assignation ()
    {
        return $this->belongsTo(CicsaAssignation::class, 'cicsa_assignation_id');
    }

    public function cicsa_feasibility_materials ()
    {
        return $this->hasMany(CicsaFeasibilityMaterial::class, 'cicsa_feasibility_id');
    }


}
