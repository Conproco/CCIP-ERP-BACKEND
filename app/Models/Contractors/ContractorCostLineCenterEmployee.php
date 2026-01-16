<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorCostLineCenterEmployee extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';

    protected $table = 'cost_line_center_employees';
    protected $fillable = [
        'cost_center_id',
        'employee_id',
    ];

    public function employee() {
        return $this->belongsTo(ContractorEmployee::class, 'employee_id');
    }


}
