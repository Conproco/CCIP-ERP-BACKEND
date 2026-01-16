<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorEmergency extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';
    protected $table = 'emergencies';

    protected $fillable = ['emergency_name','emergency_lastname','emergency_relations','emergency_phone','employee_id'];
    public function employee()
    {
        return $this->belongsTo(ContractorEmployee::class, 'employee_id');
    }
}
