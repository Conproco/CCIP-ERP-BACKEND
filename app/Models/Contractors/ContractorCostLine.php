<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorCostLine extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';

    protected $table = 'cost_lines';
    protected $fillable = [
        'name',
    ];

    public function cost_center()
    {
        return $this->hasMany(ContractorCostCenter::class);
    }
}
