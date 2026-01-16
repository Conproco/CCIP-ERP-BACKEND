<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicsaPurchaseOrderValidation extends Model
{
    use HasFactory;

    protected $table = 'cicsa_purchase_order_validations';

    protected $fillable = [
        'validation_date',
        'materials_control',
        'file_validation',
        'supervisor',
        'warehouse',
        'boss',
        'liquidator',
        'superintendent',
        'observations',
        'user_name',
        'user_id',
        'cicsa_assignation_id',
        'cicsa_purchase_order_id'
    ];

    public function user ()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cicsa_assignation ()
    {
        return $this->belongsTo(CicsaAssignation::class, 'cicsa_assignation_id');
    }

    public function cicsa_purchase_order ()
    {
        return $this->belongsTo(CicsaPurchaseOrder::class, 'cicsa_purchase_order_id');
    }
}
