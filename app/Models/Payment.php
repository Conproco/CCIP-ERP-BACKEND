<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'description',
        'operation_number',
        'register_date',
        'date',
        'state',
        'payment_doc',
        'purchase_quote_id'
    ];

    public function purchase_quote()
    {
        return $this->belongsTo(Purchase_quote::class, 'purchase_quote_id');
    }

    protected $appends = [
        'cod_payment', 
        'state'
    ];

    public function getCodPaymentAttribute()
    {
        return 'RP' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getStateAttribute()
    {
        if (!is_null($this->operation_number) && !is_null($this->date) && !is_null($this->payment_doc)) {
            return true;
        }
        return false;
    }

}
