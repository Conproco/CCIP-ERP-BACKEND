<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function provider() 
    {
        return $this->hasMany(Provider::class);
    }

    public function segment()
    {
        return $this->hasMany(Segment::class);
    }
}
