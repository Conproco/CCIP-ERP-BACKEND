<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorIssuanceDocumentation extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';

    protected $fillable = [
        'sctr',
        'policy_date',
        'policy_beneficiary',
        'emo_anexo_16',
        'emo_anexo_16_a',
        'first_aid',
        'electrical_risk',
        'safety_committee',
        'height_work',
        'claro',
        'ccip',
        'vericom_annual',
        'vericom',
        'epps_delivery',
        'fixed_documentation_id'
    ];

    //Relations
    public function fixed_documentation(){
        return $this->belongsTo(ContractorFixedDocumentation::class,'fixed_documentation_id');
    }
}
