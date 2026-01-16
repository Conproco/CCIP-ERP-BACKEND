<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subdivision_id',
        'exp_date',
        'e_employee_id',
        'employee_id',
    ];

    protected $appends = [
        'extension',
        'emp_name'
    ];

    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class, 'subdivision_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function e_employee()
    {
        return $this->belongsTo(ExternalEmployee::class, 'e_employee_id');
    }

    public function getExtensionAttribute()
    {
        $fileName = $this->title;
        $fileInfo = pathinfo($fileName);
        return $fileInfo['extension'] ?? null;
    }

    public function getEmpNameAttribute()
    {
        if ($emp = $this->employee()->first()) {
            return $emp->name . ' ' . $emp->lastname;
        }
        if ($emp = $this->e_employee()->first()) {
            return $emp->name . ' ' . $emp->lastname;
        }
        return null;
    }

}
