<?php

namespace App\Models;

use App\Models\ShoppingArea\PaymentApproval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'contact_name',
        'address',
        'phone1',
        'phone2',
        'email',
        'category_id',
        'segment',
        'zone',
        'account_number',
        'ruc',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function segments()
    {
        return $this->belongsToMany(Segment::class, 'provider_segments');
    }

    public function payment_approval()
    {
        return $this->hasOne(PaymentApproval::class);
    }

    public function room()
    {
        return $this->hasMany(Room::class);
    }
}
