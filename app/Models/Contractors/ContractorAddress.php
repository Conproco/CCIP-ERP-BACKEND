<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorAddress extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';

    protected $table = 'addresses';

    protected $fillable=['street_address','department','province','district','employee_id'];
    public function employee()
    {
        return $this->belongsTo(ContractorEmployee::class, 'employee_id');
    }
}
