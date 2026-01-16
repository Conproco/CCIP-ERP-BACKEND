<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTitle extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'state',
        'project_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id');
    } 

    public function project_codes()
    {
        return $this->hasMany(ProjectCode::class);
    } 
}
