<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorEntryDocument extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';
    protected $table = 'entry_documents';

    protected $fillable = [
        'registration_form',
        'contract',
        'certificate_discharge',
        'reading_sanctions',
        'reading_procedures',
        'annex_4_induction',
        'fixed_documentation_id'
    ];

    //Relations
    public function fixed_documentation(){
        return $this->belongsTo(ContractorFixedDocumentation::class,'fixed_documentation_id');
    }
}
