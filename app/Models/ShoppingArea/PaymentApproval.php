<?php

namespace App\Models\ShoppingArea;

use App\AuditableTrait;
use App\Models\CostLine;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentApproval extends Model
{
    use AuditableTrait;
    use HasFactory;
    protected $fillable = [
        'zone',
        'description',
        'amount',
        'account_number',
        'bank',
        'ruc',
        'beneficiary',
        'document',
        'proof_payment',
        'is_validated',
        'is_accepted',
        'reason_rejection',
        'cost_line_id',
        'provider_id',
        'user_id'
    ];

    public function getStateAttribute()
    {
        if ($this->is_accepted == '0') {
            return 'Rechazado Telecredito';
        }

        if ($this->proof_payment) {
            return 'Completado';
        }

        if ($this->is_validated == '0') {
            return 'Rechazado';
        }

        if ($this->is_validated == '1') {
            return 'Programado';
        }

        return 'Pendiente';
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function cost_line()
    {
        return $this->belongsTo(CostLine::class, 'cost_line_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
