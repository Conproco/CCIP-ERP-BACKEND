<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorFormationProgram extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';

    protected $table = "formation_programs";
    protected $fillable = [
        'name',
        'description',
    ];

    public function trainings()
    {
        return $this->belongsToMany(ContractorTraining::class,'formation_program_training' );
    }


    public function employees()
    {
        return $this->belongsToMany(ContractorEmployee::class,'employee_formation_program', 'formation_program_id', 'employee_id')->withPivot('id', 'state');
    }

}
