<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'type',
        'state',
        'external_order_number',
        'external_remission_number',
        'external_remission_date',
        'external_remission_file',
        'local_remission_number',
        'local_remission_file',
        //users
        'created_by',
        'updated_by',
        //register origin existence or other operation
        'register_id',
        'register_related_model',
        //partner involved
        'partner_id',
        'partner_related_model',
    ];

    protected $appends = [
        'code',
        'is_editable'
    ];

    public function moves() {
        return $this->hasMany(Move::class, 'operation_id');
    }

    public function getIsEditableAttribute() {
        return $this->moves()->count() === 1;
    }

    public function getCodeAttribute() {
        $formattedId = str_pad($this->id, 5, '0', STR_PAD_LEFT);
        return "OP-$formattedId";
    }
}
