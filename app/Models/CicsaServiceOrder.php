<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicsaServiceOrder extends Model
{
    use HasFactory;

    protected $table = 'cicsa_service_orders';

    protected $fillable = [
        'service_order_date',
        'service_order',
        'estimate_sheet',
        'purchase_order',
        'pdf_invoice',
        'zip_invoice',
        'document',
        'document_invoice',
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
