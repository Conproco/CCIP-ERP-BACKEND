<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagesproject extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'observation',
        'state',
        'image',
        'lat',
        'lon',
        'project_code_id'
    ];

    public function project_code()
    {
        return $this->belongsTo(ProjectCode::class, 'project_code_id');
    }
}
