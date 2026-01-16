<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ContractorEmployeeFormationProgram extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';

    protected $table = 'employee_formation_program';
    protected $fillable = [
        'state',
        'reason',
    ];

    protected $appends = [
        'urgent_state'
    ];

    public function formation_program() {
        return $this->belongsTo(ContractorFormationProgram::class, 'formation_program_id');
    }
    public function employee() {
        return $this->belongsTo(ContractorEmployee::class, 'employee_id');
    }

    public function getUrgentStateAttribute() {
        $today = Carbon::now();
        if ( $this->state === null && ($this->end_date <= $today->copy()->addDays(3)) ) return 'high';
        if ( $this->state === null
                && ($this->end_date > $today->copy()->addDays(3)
                    && $this->end_date <= $today->copy()->addDays(7)
            )) return 'medium';
        return null;
    }

}
