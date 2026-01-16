<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDetailMonetaryDiscount extends Model
{
    use HasFactory;
    protected $fillable = [
        'payroll_detail_id',
        'discount_param_id',
        'amount',
    ];

    public function payroll_detail()
    {
        return $this->belongsTo(PayrollDetail::class, 'payroll_detail_id');
    }

    public function discount_param()
    {
        return $this->belongsTo(DiscountParam::class, 'discount_param_id');
    }
}
