<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;
    protected $table = 'cost_centers';
    protected $fillable = [
        'name',
        'cost_line_id',
        'supervisor_id'
    ];

    public function clc_employees()
    {
        return $this->hasMany(CostLineCenterEmployee::class, 'cost_center_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id');
    }

    public function project()
    {
        return $this->hasMany(Project::class);
    }

    public function getProcessAttribute()
    {
        $projects = Project::where('cost_center_id', $this->id)->where('has_process', 1)->get();
        $pendiente = $projects->where('is_process_finished', 'Pendiente')->count();
        $enProceso = $projects->where('is_process_finished', 'En Proceso')->count();
        $finalizado = $projects->where('is_process_finished', 'Finalizado')->count();

        return "{$pendiente}/{$enProceso}/{$finalizado}";
    }
}
