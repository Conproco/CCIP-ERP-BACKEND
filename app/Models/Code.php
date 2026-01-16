<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $table = 'codes';
    protected $fillable = [
        'code',
        'description',
    ];


    //RELATIONS
    public function titles()
    {
        return $this->belongsToMany(Title::class, 'title_codes');
    }

    public function preprojectCodes()
    {
        return $this->hasMany(PreprojectCode::class);
    }

    public function preprojects()
    {
        return $this->belongsToMany(Preproject::class, 'preproject_codes')->withPivot('status');
    }

    public function code_images()
    {
        return $this->hasMany(CodeImage::class);
    }
}
