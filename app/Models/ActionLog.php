<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    protected $fillable = [
        'table_name',
        'row_id',
        'action',
        'data',
        'user_id',
    ];
    
    protected $casts = [
        'data' => 'array',
    ];
}
