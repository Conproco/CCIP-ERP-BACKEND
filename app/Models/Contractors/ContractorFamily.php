<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorFamily extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';
    protected $table = 'families';

    protected $fillable = ['family_dni','family_education','family_relation','family_name','family_lastname','employee_id'];
    public function employee()
    {
        return $this->belongsTo(ContractorEmployee::class, 'employee_id');
    }
}
