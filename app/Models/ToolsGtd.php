<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolsGtd extends Model
{
    use HasFactory;
    protected $fillable = [
        'code_ax',
        'name',
        'internal_reference',
        'unit'
    ];
}
