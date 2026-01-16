<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    use HasFactory;
    protected $fillable = ['emergency_name','emergency_lastname','emergency_relations','emergency_phone','employee_id'];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
