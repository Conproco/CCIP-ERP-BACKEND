<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "display_name",
        "parent_id",
        "type",
    ];

    public function submodules()
    {
        return $this->hasMany(Module::class, 'parent_id');
    }

    public function functionalities() {
        return $this->hasMany(Functionality::class, 'module_id');
    }
}
