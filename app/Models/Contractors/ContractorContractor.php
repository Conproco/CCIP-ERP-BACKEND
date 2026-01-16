<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorContractor extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';

    protected $table = 'contractors';

    protected $fillable = [
        'name'
    ];

}
