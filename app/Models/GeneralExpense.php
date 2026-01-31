<?php

namespace App\Models;

use Src\Shared\Domain\Enums\DocumentType;
use App\Constants\PintConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralExpense extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'zone',
        'location',
        'expense_type',
        'operation_number',
        'operation_date',
        'type_doc',
        'doc_date',
        'doc_number',
        'amount',
        'account_statement_id',
    ];

    protected $casts = [
        'amount' => 'double',
    ];

    protected $appends = [
        'validation',
        'bill'
    ];

    public function pext_project_expense()
    {
        return $this->hasOne(PextProjectExpense::class, "general_expense_id");
    }
    public function payroll_detail_expense()
    {
        return $this->hasOne(PayrollDetailExpense::class, "general_expense_id");
    }
    public function administrativeCost()
    {
        return $this->hasOne(AdministrativeCost::class, "general_expense_id");
    }

    public function account_statement()
    {
        return $this->belongsTo(AccountStatement::class, 'account_statement_id');
    }


    public function getValidationAttribute()
    {
        $relations = [
            $this->pext_project_expense,
            $this->payroll_detail_expense,
        ];
        $countNonNullRelations = collect($relations)->filter()->count();
        return $countNonNullRelations === 1;
    }

    public function getBillAttribute()
    {
        if (!$this->expense_type === DocumentType::FACTURA->value) {
            return ["serie" => null, "doc" => null];
        }
        if (!$this->validateDocNumber($this->doc_number)) {
            return ["serie" => null, "doc" => null];
        }
        return $this->separateDocNumber($this->doc_number);
    }


    private function validateDocNumber($input)
    {
        $pattern = '/^[a-zA-Z0-9]{4}-\d{1,10}$/';
        return preg_match($pattern, $input) === 1;
    }

    private function separateDocNumber($input)
    {
        $partes = explode('-', $input);
        $izquierda = $partes[0];
        $derecha = $partes[1];
        if (strlen($derecha) < 5) {
            $derecha = str_pad($derecha, 5, '0', STR_PAD_LEFT);
        } elseif (strlen($derecha) > 5) {
            $derecha = substr($derecha, -5);
        }
        return [
            'serie' => $izquierda,
            'doc' => $derecha,
        ];
    }

    //Attributes in common
    public function getExternalRelationAttribute()
    {
        $relation = null;
        $relationAttributes = [
            'real_state',
            'admin_state',
            'real_amount',
            'description',
        ];
        if ($this->pext_project_expense()->exists())
            $relation = $this->pext_project_expense()->first();
        if ($this->payroll_detail_expense()->exists())
            $relation = $this->payroll_detail_expense()->first();
        if ($this->administrativeCost()->exists())
            $relation = $this->administrativeCost()->first();

        return $relation->append($relationAttributes);
    }

}
