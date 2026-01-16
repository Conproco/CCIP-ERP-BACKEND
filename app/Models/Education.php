<?php

namespace App\Models;

use App\Helpers\FileHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
    protected $fillable = [
        'education_level',
        'education_status',
        'specialization',
        'curriculum_vitae',
        'employee_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
