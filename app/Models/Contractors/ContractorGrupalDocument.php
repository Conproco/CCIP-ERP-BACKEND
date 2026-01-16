<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorGrupalDocument extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';
    protected $table = 'grupal_documents';

    protected $fillable = [
        'type',
        'archive',
        'date',
        'observation',
    ];
}
