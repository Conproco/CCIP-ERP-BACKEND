<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountParam extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'concept',
    ];
}
