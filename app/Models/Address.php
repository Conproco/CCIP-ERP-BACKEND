<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable=['street_address','department','province','district','employee_id'];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
