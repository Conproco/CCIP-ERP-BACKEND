<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MonthProject extends Model
{
    use HasFactory;

    protected $fillable = ['date'];

    // Campo calculado para obtener el nombre del mes y año
    // protected $appends = ['name'];

    // public function getNameAttribute()
    // {
    //     if (preg_match('/^\d{4}-\d{2}$/', $this->date)) {
    //         return Carbon::createFromFormat('Y-m', $this->date)->translatedFormat('F Y');
    //     }
    //     return null;
    // }
}
