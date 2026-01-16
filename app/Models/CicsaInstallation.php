<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicsaInstallation extends Model
{
    use HasFactory;

    protected $table = 'cicsa_installations';

    protected $fillable = [
        'pext_date',
        'pint_date',
        'pint_amount',
        'pext_amount',
        'projected_amount',
        'conformity',
        'report',
        'shipping_report_date',
        'coordinator',
        'observation',
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

    public function cicsa_installation_materials ()
    {
        return $this->hasMany(CicsaInstallationMaterial::class, 'cicsa_installation_id');
    }
}
