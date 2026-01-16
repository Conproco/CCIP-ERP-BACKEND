<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FunctionalityPermission extends Model
{
    use HasFactory;
    protected $table = 'functionality_permissions';

    protected $fillable = [
        'functionality_id',
        'permission_id'
    ];
}
