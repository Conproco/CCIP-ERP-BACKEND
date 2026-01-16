<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorModule extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';
    protected $table = 'modules';

    protected $fillable = [
        "name",
        "display_name",
        "parent_id",
        "type",
    ];

    public function submodules()
    {
        return $this->hasMany(ContractorModule::class, 'parent_id');
    }

    public function functionalities() {
        return $this->hasMany(ContractorFunctionality::class, 'module_id');
    }
}
