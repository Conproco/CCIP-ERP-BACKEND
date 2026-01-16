<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Backlog extends Model
{
    use HasFactory;
    protected $fillable = [
        'backlog_site_id',
        'activity_type',
        'task_id',
        'system',
        'subsystem',
        'element',
        'quantity',
        'event_date',
        'report_date',
        'event_element_description',
        'state',
        'commitment',
        'c_start_date',
        'c_end_date',
        'responsible',
        'responsible_cicsa',
        'criticity',
        'conproco_area',
        'origin_event',
        'report_by',
        'report',
        'email_send',
        'budget',
        'budget_required',
        'budget_2',
    ];

    protected $appends = [
        'days_late'
    ];

    public function backlog_site() {
        return $this->belongsTo(BacklogSite::class, 'backlog_site_id');
    }

    public function getDaysLateAttribute() {
        // Verifica si el estado es "solucionado"
        if ($this->state === 'SOLUCIONADO') {
            return null;
        }

        // Verifica si event_date no es null y calcula el retraso
        if ($this->event_date) {
            $eventDate = Carbon::parse($this->event_date);
            $currentDate = Carbon::now();

            return $currentDate->diffInDays($eventDate).' días';
        }

        // Retorna null si event_date es null
        return null;
    }

}
