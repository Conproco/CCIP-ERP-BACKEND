<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorPermission extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';
    protected $table = 'permissions';

    protected $fillable = ['name', 'description'];

    public function roles()
    {
        return $this->belongsToMany(ContractorRole::class);
    }

    public function functionalities() {
        return $this->belongsToMany(ContractorFunctionality::class, 'functionality_permissions');
    }
}
