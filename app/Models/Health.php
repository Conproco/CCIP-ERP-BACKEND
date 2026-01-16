<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Health extends Model
{
    use HasFactory;
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
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
