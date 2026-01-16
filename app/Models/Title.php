<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'type'
    ];

    public function codes()
    {
        return $this->belongsToMany(Code::class, 'title_codes');
    }

}
