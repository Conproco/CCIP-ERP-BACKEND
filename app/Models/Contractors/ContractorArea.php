<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorArea extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';

    protected $table = 'areas';

    protected $fillable = [
        'name'
    ];
}
