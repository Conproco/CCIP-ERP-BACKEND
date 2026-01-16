<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'schedule_title',
        'date',
    ];

    public $appends = [
        'month', 'year'
    ];

    public function getMonthAttribute()
    {
        return Carbon::createFromFormat('Y-m-d', $this->date)->locale('es')->isoFormat('MMMM');
    }

    public function getYearAttribute()
    {
        return Carbon::createFromFormat('Y-m-d', $this->date)->format('Y');
    }

}
