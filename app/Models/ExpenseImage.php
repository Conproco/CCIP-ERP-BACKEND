<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseImage extends Model
{
    protected $fillable = [
        'pext_project_expense_id',
        'photo',
    ];

    public function pext_project_expense()
    {
        return $this->belongsTo(PextProjectExpense::class, 'pext_project_expense_id');
    }
}
