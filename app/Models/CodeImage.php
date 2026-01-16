<?php

namespace App\Models;

use App\Helpers\FileHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'code_id',
    ];

    public function code()
    {
        return $this->belongsTo(Code::class, 'code_id');
    }
}
