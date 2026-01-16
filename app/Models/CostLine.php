<?php

namespace App\Models;

use App\Models\ShoppingArea\PaymentApproval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostLine extends Model
{
    use HasFactory;
    protected $table = 'cost_lines';
    protected $fillable = [
        'name',
    ];

    public function cost_center()
    {
        return $this->hasMany(CostCenter::class);
    }

    public function payment_approval()
    {
        return $this->hasMany(PaymentApproval::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'cost_line_id');
    }
}
