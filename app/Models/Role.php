<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    
    public function functionalities()
    {
        return $this->belongsToMany(Functionality::class, 'role_functionalities');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getCurrentModules()
    {
        $subModules = [];
        $modules = [];
        foreach ($this->functionalities()->get() as $func) {
            $module = Module::find($func->module_id);
            $parent = Module::find($module->parent_id);
            if ($module && !in_array($module->name, $subModules)) {
                $subModules[] = $module->name;
            }
            if ($parent && !in_array($parent->name, $modules)) {
                $modules[] = $parent->name;
            }
        }
        return [
            'modules' => $modules,
            'submodules' => $subModules
        ];
    }

}
