<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdivision extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'section_id', 'is_visible'];

    public function documents()
    {
        return $this->hasMany(Document::class, 'subdivision_id');
    }
    public function section()
    {
        return $this->belongsTo(DocumentSection::class, 'section_id');
    }
}
