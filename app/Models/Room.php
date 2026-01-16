<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'rental_type',
        'address',
        'observations',
        'provider_id',
        'cost_line_id',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function costline()
    {
        return $this->belongsTo(CostLine::class, 'cost_line_id');
    }

    public function room_documents()
    {
        return $this->hasMany(RoomDocument::class, 'room_id');
    }

    public function room_images()
    {
        return $this->hasMany(RoomImage::class, 'room_id');
    }

    public function getDocumentsToExpireAttribute()
    {
        return $this->room_documents()->get()->filter(fn($rm)=>$rm->expiration_state)->count();
    }

}
