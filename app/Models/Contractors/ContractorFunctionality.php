<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorFunctionality extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';

    protected $table = 'functionalities';

    protected $fillable = [
        "key_name",
        "display_name",
        "module_id",
    ];
}
