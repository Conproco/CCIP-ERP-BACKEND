<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Constants\Inventory\Move as MoveConstants;

class Move extends Model
{
    protected $fillable = [
        'type',
        'state',
        'operation_id',
        'product_id',
        'location_origin_id',
        'location_destiny_id',
        'cost_center_id',
        'project_id',
        'employee_id',
        'unit_id',
        'quantity',
        'serial_number',
        'confirmation_date',
        //next move
        'prev_move_id',
        'observations',
         //register origin ---> the lines of purchases or sales or other stock move
        'register_id',
        'register_related_model',
    ];

    protected $appends = [
        'assigned_code',
        'project_name',
        'employee_name',
        'available_quantity',
        'used_quantity',
        'reserved_quantity',
        'code',
        'is_editable',
        'is_duplicable',
        'is_operation_editable',
        'allow_next',
        'er_state',
    ];

   
    public function product () {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function unit () {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function location_origin () {
        return $this->belongsTo(Location::class, 'location_origin_id');
    }

    public function location_destiny () {
        return $this->belongsTo(Location::class, 'location_destiny_id');
    }
    public function cost_center () {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }
    public function project () {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function next_moves() {
        return $this->hasMany(Move::class, 'prev_move_id');
    }

    public function operation () {
        return $this->belongsTo(Operation::class, 'operation_id');
    }

    public function valuation () {
        return $this->hasOne(Valuation::class, 'move_id');
    }

    public function getProjectNameAttribute() {
        return $this->project()->getResults()?->project_name;
    }

    public function getEmployeeNameAttribute()
    {
        $emp = $this->employee()->getResults();
        return $emp ? "$emp->name $emp->lastname" : '';
    }

    public function getUsedQuantityAttribute() {
        return $this->valuation()->getResults()->used_quantity;
    }

    public function getAvailableQuantityAttribute() {
        return $this->quantity 
            - $this->getUsedQuantityAttribute()
            - $this->getReservedQuantityAttribute();
    }

    public function getReservedQuantityAttribute() {
        return $this->next_moves()->where('state', MoveConstants::draft)->sum('quantity');
    }

    public function getIsDuplicableAttribute() {
        return ! (bool) $this->prev_move_id;
    }

    public function getCodeAttribute() {
        $formattedId = str_pad($this->id, 5, '0', STR_PAD_LEFT);
        return "MOV-$formattedId";
    }
    public function getIsEditableAttribute() {
        return $this->state === MoveConstants::draft;
    }
    public function getIsOperationEditableAttribute() {
        if (!$this->operation()->first()) return true;
        return $this->operation()->first()->is_editable;
    }
    public function getAllowNextAttribute() {
        return $this->state === MoveConstants::done;
    }

    public function getAssignedCodeAttribute()
    {
        $emp = $this->employee()->getResults();
        if ($emp) {
            $initials = strtoupper(substr($emp->name, 0, 1) . substr($emp->lastname, 0, 1));
            return "EMP$initials";
        }
        return '';
    }

    public function getErStateAttribute()
    {
        $operation = $this->operation;
        if (!$operation)
            return false;
        return !empty($operation->external_remission_number) && !empty($operation->external_remission_date);
    }

    protected static function booted()
    {
        static::created(function ($move) {
            Valuation::create([
                'product_id' => $move->product_id,
                'total_quantity' => $move->quantity,
                'unit_cost' => 0,
                'move_id' => $move->id,
                'used_quantity' => 0,
            ]);
        });
    }

}
