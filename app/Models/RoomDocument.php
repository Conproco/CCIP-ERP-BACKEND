<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class RoomDocument extends Model
{
    use HasFactory;

    protected $table = 'room_documents';

    protected $fillable = [
        'archive',
        'observations',
        'expiration_date',
        'room_id',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }


    public function getExpirationStateAttribute () {
        $today = Carbon::now();
        $expirationThreshold = $today->copy()->addDays(7);
        return $this->expiration_date <= $expirationThreshold;
    }
}
