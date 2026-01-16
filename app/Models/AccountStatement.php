<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountStatement extends Model
{
    use HasFactory;
    protected $fillable = [
        'operation_date',
        'operation_number',
        'beneficiary',
        'description',
        'charge',
        'payment',
    ];

    public function general_expenses()
    {
        return $this->hasMany(GeneralExpense::class, 'account_statement_id');
    }


    public function getStateAttribute()
    {
        if ($this->operation_number === null) {
            return 'Validado';
        }
        if ($this->payment) {
            return 'Abono';
        }
        if ($this->charge) {
            $total = $this->general_expenses()->get()->sum('amount');
            $chargeFormatted = number_format($this->charge, 2, '.', '');
            $totalFormatted = number_format($total, 2, '.', '');
            if ((float)$totalFormatted === (float)$chargeFormatted) {
                return 'Validado';
            } elseif ((float)$totalFormatted > (float)$chargeFormatted) {
                return 'Excedido';
            } elseif ((float)$totalFormatted > 0) {
                return 'Por validar';
            } else {
                return 'No validado';
            }
        }
    }
}
