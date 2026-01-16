<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectQuoteCode extends Model
{
    protected $fillable = [
        'project_id',
        'project_quote_id',
        'serie',
    ];
}
