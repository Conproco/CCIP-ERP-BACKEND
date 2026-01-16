<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supervisor extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'name',
        'phone',
    ];

    public function cost_center()
    {
        return $this->hasMany(CostCenter::class);
    }

    public function project()
    {
        return $this->hasMany(Project::class);
    }
}
