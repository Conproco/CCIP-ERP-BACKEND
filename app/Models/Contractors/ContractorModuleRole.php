<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorModuleRole extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';

    protected $table = 'module_role';

    protected $fillable = [
        'role_id',
        'module_id'
    ];
}
