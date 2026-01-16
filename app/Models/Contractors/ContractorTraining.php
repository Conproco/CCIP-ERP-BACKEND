<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorTraining extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';
    protected $table = 'trainings';

    protected $fillable = [
        'name',
        'description'
    ];
}
