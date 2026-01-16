<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDeductionInstallment extends Model
{
    protected $fillable = [
        'approximate_payment_date',
        'deposit_voucher',
        'amount',
        'payment_status',
        'operation_date',
        'operation_number',
        'employee_id',
        'payroll_deduction_id',
        'payroll_detail_monetary_discount_id',
        'general_expense_id'
    ];

    public function payroll_deduction()
    {
        return $this->belongsTo(PayrollDeduction::class);
    }

    public function payroll_detail_monetary_discount()
    {
        return $this->belongsTo(PayrollDetailMonetaryDiscount::class);
    }
}
