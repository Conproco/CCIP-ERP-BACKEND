<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projectimage extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'image',
        'project_id'
    ];

    public function project(){
        return $this->belongsTo(Project::class,'project_id');
    }
}
