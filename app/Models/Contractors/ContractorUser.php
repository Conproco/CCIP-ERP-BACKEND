<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Model;

class ContractorUser extends Model
{
    protected $connection = 'mysql_contractors';

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'dni',
        'platform',
        'password',
        'role_id',
        'area_id',
        'phone'
    ];

    public function role()
    {
        return $this->belongsTo(ContractorRole::class, 'role_id');
    }

    public function area()
    {
        return $this->belongsTo(ContractorArea::class, 'area_id');
    }

    public function contractors()
    {
        return $this->belongsToMany(ContractorUser::class, 'contractor_users');
    }

    public function contractor_user()
    {
        return $this->hasOne(ContractorContractorUser::class, 'user_id');
    }
}
