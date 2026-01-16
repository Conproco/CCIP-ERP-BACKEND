<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CicsaChargeArea extends Model
{
    use HasFactory;

    protected $table = 'cicsa_charge_areas';

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'credit_to',
        'amount',
        'state_detraction',

        'deposit_date',
        'transaction_number_current',
        'checking_account_amount',
        'deposit_date_bank',
        'transaction_number_bank',
        'amount_bank',
        'document',

        'user_name',
        'user_id',
        'cicsa_assignation_id',
        'cicsa_purchase_order_id'
    ];

    protected $casts = [
        'amount' => 'double',
        'checking_account_amount' => 'float',
        'amount_bank' => 'float',
        'credit_to' => 'integer',
    ];

    protected $appends = [
        'payment_date',
        'days_late',
        'state'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cicsa_assignation()
    {
        return $this->belongsTo(CicsaAssignation::class, 'cicsa_assignation_id');
    }

    public function cicsa_purchase_order()
    {
        return $this->belongsTo(CicsaPurchaseOrder::class, 'cicsa_purchase_order_id');
    }

    public function getPaymentDateAttribute()
    {
        if (!empty($this->invoice_date) && !empty($this->credit_to)) {
            $invoiceDate = Carbon::parse($this->invoice_date);
            $invoiceDate->addDays($this->credit_to);
            return $invoiceDate->toDateString();
        }

        return null;
    }

    public function getDaysLateAttribute()
    {
        if (!empty($this->deposit_date) && !empty($this->payment_date)) {
            $deposit_date = Carbon::parse($this->deposit_date);
            $payment_date = Carbon::parse($this->payment_date);
            $daysLate = $payment_date->diffInDays($deposit_date, false);
            return $daysLate > 0 ? $daysLate : 0;
        } elseif ($this->payment_date) {
            $paymentDate = Carbon::parse($this->payment_date);
            $currentDate = Carbon::now();
            $daysLate = $paymentDate->diffInDays($currentDate, false);
            return $daysLate > 0 ? $daysLate : 0;
        }
        return 0;
    }

    public function getStateAttribute()
    {
        if ($this->state_detraction === 0 && $this->transaction_number_current !== null) {
            return 'Pagado';
        }

        if ($this->deposit_date === null && Carbon::now() < $this->payment_date) {
            return 'A tiempo';
        }

        if ($this->transaction_number_current !== null && $this->transaction_number_bank !== null) {
            return 'Pagado';
        }

        if ($this->deposit_date === null && Carbon::now() > $this->payment_date !== null) {
            return 'Con deuda';
        }

        return 'En Proceso';
    }
}
