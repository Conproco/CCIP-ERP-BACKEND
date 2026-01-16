<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorCostCenter extends Model
{
    use HasFactory;

    protected $connection = 'mysql_contractors';

    protected $table = 'cost_centers';
    protected $fillable = [
        'name',
        'cost_line_id',
        'percentage',
    ];

    public function clc_employees(){
        return $this->hasMany(ContractorCostLineCenterEmployee::class, 'cost_center_id');
    }

}
