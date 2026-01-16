<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandModel extends Model
{
    use HasFactory;

    protected $table = 'brand_models';

    protected $fillable = [
        'name',
        'brand_id'
    ];

    public function brand ()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

        //buena paucar

}
