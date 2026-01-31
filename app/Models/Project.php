<?php

namespace App\Models;

use Src\Shared\Infrastructure\Persistence\Traits\AuditableTrait;
use Src\Shared\Domain\Enums\ExpenseType;
use App\Constants\PintConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    use AuditableTrait;
    protected $table = 'projects';
    protected $fillable = [
        'project_name',
        'cpe',
        'zone',
        'zone2',
        'zone3',
        'description',
        'status',
        'initial_budget',
        'need_budget',
        'position',
        'year',
        'has_process',
        'type_project',
        'is_expense_finished',
        'is_process_finished',
        'cost_center_id',
        'cost_line_id',
        'supervisor_id'
    ];

    // CALCULATED
    public function getCurrentBudgetAttribute()
    {
        $lastUpdate = $this->budget_updates()->latest()->first();
        $currentBudget = $lastUpdate ? $lastUpdate->new_budget : $this->initial_budget;
        return $currentBudget;
    }

    public function getRemainingBudgetAttribute()
    {
        if ($this->initial_budget === null) {
            return 0.00;
        }
        $lastUpdate = $this->budget_updates()->latest()->first();
        $currentBudget = $lastUpdate ? $lastUpdate->new_budget : $this->initial_budget;
        $expenses = $this->getExpensesTotalAttribute();
        $currentBudget = $currentBudget - $expenses;
        return $currentBudget;
    }

    public function getExpensesTotalAttribute()
    {
        return $this->pext_project_expenses()
            ->where('is_accepted', 1)
            ->where('fixedOrAdditional', 'Variables')
            ->whereNotIn('expense_type', ExpenseType::thatDontCount())
            ->get()
            ->sum(function ($expense) {
                return $expense->real_amount;
            });
    }

    public function getSerializedCodeAttribute()
    {
        $costLine = CostLine::find($this->cost_line_id);
        return $costLine->name . '-' . $this->year . '-' . str_pad($this->position, 4, '0', STR_PAD_LEFT);
    }

    //RELATIONS
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user')->withTimestamps();
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'project_employee')->withPivot('charge', 'id');
    }

    public function budget_updates()
    {
        return $this->hasMany(BudgetUpdate::class);
    }

    public function project_image()
    {
        return $this->hasMany(Projectimage::class);
    }

    public function cicsa_assignation()
    {
        return $this->hasOne(CicsaAssignation::class);
    }

    public function cost_line()
    {
        return $this->belongsTo(CostLine::class, 'cost_line_id');
    }

    public function cost_center()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function pext_project_expenses()
    {
        return $this->hasMany(PextProjectExpense::class);
    }

    public function project_quotes()
    {
        return $this->hasMany(ProjectQuote::class)->orderBy('created_at', 'asc');
        ;
    }

    public function projectTitles()
    {
        return $this->hasMany(ProjectTitle::class);
    }
}
