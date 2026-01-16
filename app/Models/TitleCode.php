<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_id',
        'code_id'
    ];




}
