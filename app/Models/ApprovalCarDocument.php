<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalCarDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'ownership_card',
        'technical_review',
        'technical_review_date',
        'soat',
        'soat_date',
        'insurance',
        'insurance_date',
        'address_web',
        'user',
        'password',
        'car_document_id',
    ];

    //relations
    public function car_document()
    {
        return $this->belongsTo(CarDocument::class, 'car_document_id');
    }
}
