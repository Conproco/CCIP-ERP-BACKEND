<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;
    protected $fillable = ['family_dni','family_education','family_relation','family_name','family_lastname','employee_id'];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
