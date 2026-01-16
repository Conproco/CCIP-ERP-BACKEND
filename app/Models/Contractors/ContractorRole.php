<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorRole extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';
    protected $table = 'roles';

    protected $fillable = ['name', 'description'];

    public function permissions()
    {
        return $this->belongsToMany(ContractorPermission::class);
    }

    public function functionalities()
    {
        return $this->belongsToMany(ContractorFunctionality::class, 'role_functionalities');
    }

    public function modules()
    {
        return $this->belongsToMany(ContractorModule::class);
    }

    public function users()
    {
        return $this->hasMany(ContractorUser::class);
    }

    public function getCurrentModules()
    {
        $subModules = [];
        $modules = [];
        foreach ($this->functionalities()->get() as $func) {
            $module = ContractorModule::find($func->module_id);
            $parent = ContractorModule::find($module->parent_id);
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
