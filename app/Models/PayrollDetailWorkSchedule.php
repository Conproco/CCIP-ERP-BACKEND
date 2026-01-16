<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDetailWorkSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        "payroll_detail_id",
        "subsidized_days",
        "non_subsidized_days",
        "regular_hours",
        "overtime_hours",
    ];

    protected $casts = [
        'subsidized_days' => 'array',
        'non_subsidized_days' => 'array',
    ];
    

    public function payroll_detail () {
        return $this->belongsTo(PayrollDetail::class);
    }
}
