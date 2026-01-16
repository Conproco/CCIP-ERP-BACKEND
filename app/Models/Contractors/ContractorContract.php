<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorContract extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';

    protected $table = 'contracts';

    protected $fillable = [
        'cost_line_id',
        'state_travel_expenses',
        'type_contract',
        'amount_travel_expenses',
        'basic_salary',
        'nro_cuenta',
        'life_ley',
        'discount_remuneration',
        'discount_sctr',
        'state',
        'days_taken',
        'hire_date',
        'fired_date',
        'employee_id',
        'pension_type',
        'personal_segment',
    ];

    //RELATIONS

    public function employee()
    {
        return $this->belongsTo(ContractorEmployee::class, 'employee_id');
    }
    public function cost_line()
    {
        return $this->belongsTo(ContractorCostLine::class, 'cost_line_id');
    }

    //CALCULATED




    public function hideAllAppends()
    {
        // Obtén todos los atributos 'appends'
        $appends = $this->getAppends();
        // Oculta todos los atributos 'appends'
        return $this->makeHidden($appends);
    }
}
