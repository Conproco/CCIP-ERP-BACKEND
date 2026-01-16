<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollExternalDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        "payroll_id",
        "name",
        "lastname",
        "doc_type",
        "doc_number",
        "amount",
        "ret_tax",
    ];

    public function external_employee () {
        return $this->belongsTo(ExternalEmployee::class);
    }
}
