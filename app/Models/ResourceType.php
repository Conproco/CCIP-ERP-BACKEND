<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'depreciation_value',
        'timelife'
    ];

    public function purchase_product()
    {
        return $this->hasMany(Purchase_product::class);
    }
    
}
