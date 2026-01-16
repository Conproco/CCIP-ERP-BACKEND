<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DocumentRegister extends Model
{
    use HasFactory;
    protected $fillable = [
        'subdivision_id',
        'document_id',
        'employee_id',
        'e_employee_id',
        'exp_date',
        'state',
        'observations',
    ];

    protected $appends = [
        'sync_status',
        'display'
    ];
    
    public function document () {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function employee () {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function subdivision () {
        return $this->belongsTo(Subdivision::class, 'subdivision_id');
    }

    public function getSyncStatusAttribute () {
        return true;
    }

    public function getDisplayAttribute () {
        if (is_null($this->exp_date)) {
            return false;
        }
        $expDate = Carbon::parse($this->exp_date);
        $now = Carbon::now();
        return $expDate->lte($now->addWeek());
    }
}
