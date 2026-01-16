<?php

namespace App\Models;

use App\AuditableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constants\PintConstants;
use App\Constants\ProjectConstants;

class AdministrativeCost extends Model
{
    use HasFactory;
    use AuditableTrait;
    protected $fillable = [
        'expense_type',
        'ruc',
        'type_doc',
        'zone',
        'operation_number',
        'operation_date',
        'doc_number',
        'doc_date',
        'description',
        'amount',
        'igv',
        'photo',
        'user_id',
        'general_expense_id',
        'month_project_id',
        'provider_id',
    ];

    protected $casts = [
        'amount' => 'double',
    ];

    protected $appends = [
        'real_amount',
        'real_state'
    ];

    public function general_expense()
    {
        return $this->belongsTo(GeneralExpense::class, 'general_expense_id');
    }

    public function month_project()
    {
        return $this->belongsTo(MonthProject::class, 'month_project_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function getRealAmountAttribute()
    {
        return $this->amount / (1 + $this->igv / 100);
    }

    public function getRealStateAttribute()
    {
        if ($this->general_expense()->first()?->account_statement_id) {
            return PintConstants::ACEPTADO_VALIDADO;
        }
        return PintConstants::PENDIENTE;
    }
}
