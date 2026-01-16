<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'lastname',
        'cropped_image',
        'gender',
        'state_civil',
        'birthdate',
        'dni',
        'email',
        'email_company',
        'phone1',
        'l_policy',
        'sctr_exp_date',
        'policy_exp_date',
        'user_id',
    ];

    // protected $appends = [
    //     'sctr_about_to_expire',
    //     'policy_about_to_expire',
    //     'documents_about_to_expire',
    // ];

    //RELATIONS

    public function getTypeAttribute()
    {
        return 'employees';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
    public function education()
    {
        return $this->hasOne(Education::class);
    }
    public function address()
    {
        return $this->hasOne(Address::class);
    }
    public function emergency()
    {
        return $this->hasMany(Emergency::class);
    }
    public function family()
    {
        return $this->hasMany(Family::class);
    }
    public function health()
    {
        return $this->hasOne(Health::class);
    }

    public function document_registers()
    {
        return $this->hasMany(DocumentRegister::class, 'employee_id');
    }
    public function documents()
    {
        return $this->hasMany(Document::class, 'employee_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_employee')->withPivot('charge');
    }

    // public function project_employees()
    // {
    //     return $this->hasOne(ProjectEmployee::class);
    // }

    public function payroll_details()
    {
        return $this->hasMany(PayrollDetail::class, 'employee_id', 'id');
    }

    public function payrolls()
    {
        return $this->belongsToMany(Payroll::class, 'payroll_details');
    }

    public function pensions()
    {
        return $this->belongsToMany(Pension::class, 'payroll_details');
    }

    public function salaryPerDay($days)
    {
        return $this->contract()->first()->basic_salary / $days;
    }

    public function getSctrAboutToExpireAttribute()
    {
        if (
            $this->contract()->first()?->discount_sctr
            && $this->sctr_exp_date
        ) {
            $actual = Carbon::now()->addDays(7);
            $exp_date = Carbon::parse($this->sctr_exp_date);
            return $actual >= $exp_date;
        }
        return null;
    }

    public function getPolicyAboutToExpireAttribute()
    {
        if (
            $this->contract()->first()?->life_ley
            && $this->policy_exp_date
        ) {
            $actual = Carbon::now()->addDays(7);
            $exp_date = Carbon::parse($this->policy_exp_date);
            return $actual >= $exp_date;
        }
        return null;
    }

    public function getDocumentsAboutToExpireAttribute()
    {
        $total = $this->document_registers()->get()->filter(function ($item) {
            return $item->display === true;
        })
            ->sum('display');
        if ($this->sctr_about_to_expire) {
            $total += 1;
        }
        if ($this->policy_about_to_expire) {
            $total += 1;
        }
        return $total;
    }
    public function getNoDocumentsAttribute()
    {
        $missing = Subdivision::where('section_id', '<=', 10)
            ->whereNotIn('id', $this->document_registers()->pluck('subdivision_id'))
            ->exists();
        return $missing;
    }
    public function getTimeWorkedAttribute()
    {
        $hireDate = Carbon::parse($this->contract()->first()->hire_date);
        $now = Carbon::now();
        $diff = $hireDate->diff($now);
        $years = $diff->y;
        $months = $diff->m;
        $days = $diff->d;
        $parts = [];

        if ($years > 0) $parts[] = "{$years} " . ($years === 1 ? 'año' : 'años');
        if ($months > 0)  $parts[] = "{$months} " . ($months === 1 ? 'mes' : 'meses');
        if ($days > 0 || empty($parts)) $parts[] = "{$days} " . ($days === 1 ? 'día' : 'días');
        return implode(', ', $parts);
    }
}
