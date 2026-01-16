<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorDocumentSection extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';
    protected $table = 'document_sections';

    protected $fillable = ['name', 'is_visible'];

    public function subdivisions()
    {
        return $this->hasMany(ContractorSubdivision::class, 'section_id');
    }
}
