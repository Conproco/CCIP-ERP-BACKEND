<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorSubdivision extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';
    protected $table = 'subdivisions';

    protected $fillable = ['name', 'section_id', 'is_visible'];

    public function documents()
    {
        return $this->hasMany(ContractorDocument::class, 'subdivision_id');
    }
    public function section()
    {
        return $this->belongsTo(ContractorDocumentSection::class, 'section_id');
    }
}
