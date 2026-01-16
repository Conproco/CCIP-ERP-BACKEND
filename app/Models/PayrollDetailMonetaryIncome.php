<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDetailMonetaryIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_detail_id',
        'income_param_id',
        'accrued_amount',
        'paid_amount',
    ];

    public function payroll_detail()
    {
        return $this->belongsTo(PayrollDetail::class, 'payroll_detail_id');
    }

    public function income_param()
    {
        return $this->belongsTo(IncomeParam::class, 'income_param_id');
    }
}
