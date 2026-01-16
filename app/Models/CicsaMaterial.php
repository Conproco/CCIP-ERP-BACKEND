<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicsaMaterial extends Model
{
    use HasFactory;

    protected $table = 'cicsa_materials';

    protected $fillable = [
        'pick_date',
        'guide_number',
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
    public function cicsa_material_items ()
    {
        return $this->hasMany(CicsaMaterialsItem::class, 'cicsa_material_id');
    }
}

