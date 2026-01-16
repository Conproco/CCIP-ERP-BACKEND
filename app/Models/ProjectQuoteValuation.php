<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectQuoteValuation extends Model
{
    use HasFactory;
    protected $fillable=[
        'days',
        'unit',
        'metrado',
        'unit_value',
        'description',
        'project_quote_id'
    ];

    public function project_quote()
    {
        return $this->belongsTo(ProjectQuote::class, 'project_quote_id');
    }
}
