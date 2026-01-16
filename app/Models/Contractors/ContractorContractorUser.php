<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorContractorUser extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';

    protected $table = 'contractor_users';

    protected $fillable = [
        'contractor_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(ContractorUser::class, 'user_id');
    }
    public function contractor()
    {
        return $this->belongsTo(ContractorContractor::class, 'contractor_id');
    }

    


}
