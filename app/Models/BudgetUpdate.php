<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetUpdate extends Model
{
    use HasFactory;
    protected $table = 'budget_updates';
    protected $fillable = [
        'new_budget',
        'difference',
        'project_id',
        'reason',     
        'user_id',
        "user_name",
        'created_at'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
