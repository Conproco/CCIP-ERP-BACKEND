<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarDocument extends Model
{
    use HasFactory;

    protected $table = 'car_documents';

    protected $fillable = [
        'ownership_card',
        'technical_review',
        'technical_review_date',
        'soat',
        'soat_date',
        'insurance',
        'insurance_date',
        'rental_contract',
        'rental_contract_date',
        'address_web',
        'user',
        'password',
        'car_id',
    ];

    //relations
    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function approvel_car_document()
    {
        return $this->hasMany(ApprovalCarDocument::class);
    }
}
