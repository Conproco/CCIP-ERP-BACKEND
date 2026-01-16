<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleDriveEvent extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'event_type', 'file_id', 'file_name'];
}
