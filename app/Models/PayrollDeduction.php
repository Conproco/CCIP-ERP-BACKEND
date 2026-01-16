<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDeduction extends Model
{
    protected $fillable = [
        'reason',
        'deposit_voucher',
        'operation_number',
        'operation_date',
        'authorization_file',
        'observations',
        'employee_id'
    ];

    public function  getTotalAmountAttribute()
    {
        return $this->payroll_deduction_installment->sum('amount');
    }

    public function getStatusAttribute()
    {
        $installments = $this->payroll_deduction_installment()->get();
        $countPaid = $installments->where('payment_status', 'Pagado')->count();
        $total = $installments->count();

        if ($countPaid === 0) {
            return 'Pendiente';
        }

        if ($countPaid === $total) {
            return 'Pagado';
        }

        return 'En proceso';
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payroll_deduction_installment()
    {
        return $this->hasMany(PayrollDeductionInstallment::class);
    }
}
