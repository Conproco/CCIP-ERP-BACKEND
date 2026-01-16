<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pension extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'commission_flow',
        'annual_commission_balance',
        'insurance_premium',
        'mandatory_contribution',
        'payroll_id'
    ];



    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'payroll_details')
            ->withPivot('pension_id');
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }
}
