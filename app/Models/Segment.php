<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Segment extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id'
    ];

    public function category() : BelongsTo {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function provider(){
        return $this->belongsToMany(Provider::class,'provider_segments');
    }
}
