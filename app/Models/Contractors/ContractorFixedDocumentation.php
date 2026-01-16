<?php

namespace App\Models\Contractors;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractorFixedDocumentation extends Model
{
    use HasFactory;
    protected $connection = 'mysql_contractors';
    protected $table = 'fixed_documentations';

    protected $fillable = [
        'employees',
        'dni',
        'driver_license',
        'registration_form',
        'home_verification',
        'vericom_data_authorization',
        'dj_pension_system',
        'electricity_water_receipt',
        'curriculum',
        'digital_photo',
        'signature',
        'vaccination_card'
    ];

    public function entry_document(){
        return $this->hasOne(ContractorEntryDocument::class);
    }

    public function issuance_documentation(){
        return $this->hasOne(ContractorIssuanceDocumentation::class);
    }
}
