<?php

namespace App\Models;

use Src\Shared\Infrastructure\Persistence\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\HumanResource\Domain\Enums\Payroll\PayrollExpenseStateType;

class PayrollDetailExpense extends Model
{
    use HasFactory;
    use AuditableTrait;
    protected $fillable = [
        'employee_name',
        'expense_type',
        'operation_number',
        'operation_date',
        'type_doc',
        'doc_number',
        'doc_date',
        'payroll_detail_id',
        'general_expense_id',
        'photo',
        'amount'
    ];


    public function general_expense()
    {
        return $this->belongsTo(GeneralExpense::class, 'general_expense_id');
    }

    public function payroll_detail(): BelongsTo
    {
        return $this->belongsTo(PayrollDetail::class, 'payroll_detail_id');
    }


    public function getDescriptionAttribute()
    {
        return "Pago de nómina de $this->employee_name";
    }


    public function getRealStateAttribute()
    {
        if ($this->general_expense()->first()?->account_statement_id) {
            return PayrollExpenseStateType::ACEPTADO_VALIDADO->value;
        }
        return PayrollExpenseStateType::PENDIENTE->value;
    }


}
