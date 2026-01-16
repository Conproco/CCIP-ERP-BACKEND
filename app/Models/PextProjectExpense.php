<?php

namespace App\Models;

use App\AuditableTrait;
use App\Constants\PintConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PextProjectExpense extends Model
{
    use HasFactory;
    use AuditableTrait;

    protected $fillable = [
        'fixedOrAdditional',
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
        // 'photo',
        'is_accepted',
        'igv',
        'rejected_reason',
        'admin_is_accepted',
        'admin_reject_reason',
        'user_id',
        'project_id',
        'provider_id',
        'general_expense_id',
        //to divide
        'parent_id'

    ];

    protected $appends = [
        'origin',
        'parent_code',
        'code',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function expense_image()
    {
        return $this->hasMany(ExpenseImage::class);
    }

    public function general_expense()
    {
        return $this->belongsTo(GeneralExpense::class, 'general_expense_id');
    }

    public function childs()
    {
        return $this->hasMany(PextProjectExpense::class, 'parent_id');
    }

    public function getOriginAttribute()
    {
        return $this->parent()->getResults()
            ? PintConstants::PARTICION
            : PintConstants::ORIGINAL;
    }

    public function parent()
    {
        return $this->belongsTo(PextProjectExpense::class, 'parent_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRealAmountAttribute()
    {
        return $this->amount / (1 + $this->igv / 100);
    }

    public function getCodeAttribute()
    {
        $formattedId = str_pad($this->id, 6, '0', STR_PAD_LEFT);
        return "EXP-$formattedId";
    }

    public function getParentCodeAttribute()
    {
        return $this->parent()->getResults()?->code;
    }

    public function getRealStateAttribute()
    {
        if ($this->is_accepted === 0) {
            return PintConstants::RECHAZADO;
        }
        if ($this->is_accepted && $this->general_expense()->first()?->account_statement_id) {
            return PintConstants::ACEPTADO_VALIDADO;
        }
        if ($this->is_accepted) {
            return PintConstants::ACEPTADO;
        }
        return PintConstants::PENDIENTE;
    }

    public function getAdminStateAttribute()
    {
        if ($this->is_accepted === null) {
            return PintConstants::NO_DISPONIBLE;
        }
        if ($this->admin_is_accepted === 0) {
            return PintConstants::RECHAZADO;
        }
        if ($this->admin_is_accepted) {
            return PintConstants::ACEPTADO;
        }
        return PintConstants::PENDIENTE;
    }
}
