<?php

namespace App\Models;

use App\AuditableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Constants\PintConstants;

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


    public function getDescriptionAttribute(){
        return "Pago de nómina de $this->employee_name";
    }


    public function getRealStateAttribute() {
        if ($this->general_expense()->first()?->account_statement_id) {
            return PintConstants::ACEPTADO_VALIDADO;
        }
        return PintConstants::PENDIENTE;
    }

 
}
