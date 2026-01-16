<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectQuote extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_place',
        'delivery_time',
        'observations',
        'fee',
        'project_id',
        'user_id'
    ];

    protected $casts = [
        'fee' => 'boolean',
    ];

    protected $appends = [
        'serialized_code'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project_quote_valuations()
    {
        return $this->hasMany(ProjectQuoteValuation::class);
    }

    public function project_quote_code() {
        return $this->hasOne(ProjectQuoteCode::class, 'project_quote_id');
    }

    public function getSerializedCodeAttribute() {
    $code = $this->project_quote_code()->first();
    if (!$code) return null;
    $date = $this->created_at->format('d/m/Y'); 
    $serie = str_pad($code->serie, 2, '0', STR_PAD_LEFT); 
    return "Q-{$date}-{$serie}";
}
}
