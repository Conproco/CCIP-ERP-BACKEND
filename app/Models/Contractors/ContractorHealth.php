<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorHealth extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';
    protected $table = 'healths';

    protected $fillable = [
        'blood_group',
        'weight',
        'height',
        'shoe_size',
        'shirt_size',
        'pants_size',
        'medical_condition',
        'allergies',
        'operations',
        'accidents',
        'vaccinations',
        'employee_id'
    ];
    public function employee()
    {
        return $this->belongsTo(ContractorEmployee::class, 'employee_id');
    }
}
