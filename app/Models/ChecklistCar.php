<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'km',
        'reason',
        'user_id',
        'car_id',
        'user_name',
        'additionalEmployees',
        'zone',
        'plate',
        'hornState',
        'brakesState',
        'headlightsState',
        'intermitentlightState',
        'indicatorsState',
        'mirrorsState',
        'tiresState',
        'bumpersState',
        'temperatureGauge',
        'oilGauge',
        'fuelGauge',
        'vehicleCleanliness',
        'doorsState',
        'windshieldState',
        'engineState',
        'batteryState',

        'jack',
        'spareTire',
        'towCable',
        'batteryCable',
        'reflector',
        'emergencyKit',
        'alarm',
        'chocks',
        'ladderHolder',
        'sidePlate',
        
        'maintenanceTools',
        'preventionTools',
        'imageSpareTire',
        
        'front',
        'leftSide',
        'rightSide',
        'interior',
        'rearLeftTire',
        'rearRightTire',
        'frontRightTire',
        'frontLeftTire',

        'beak',
        'shovel',
        'gps',
        'extinguisher',
        'firstAidKit',
        'rollCage',
        'fogLights',
        'protectionCage',
        'hoopInsurance',
        'headlightInsurance',
        'cardProtector',
        'cones',
        'safetyTriangle',
        'wheelWrench',
        
        'back',
        'dashboard',
        'rearSeat',

        'observation',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');    
    }
    
    public function car() {
        return $this->belongsTo(Car::class, 'car_id');    
    }
}
