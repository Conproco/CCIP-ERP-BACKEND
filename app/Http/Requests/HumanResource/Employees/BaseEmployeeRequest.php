<?php

namespace App\Http\Requests\HumanResource\Employees;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;

abstract class BaseEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas comunes para Crear y Editar.
     * Las reglas 'unique' se definen en las clases hijas.
     */
    public function rules(): array
    {
        return [
            // --- Archivos ---
            'curriculum_vitae' => 'nullable|max:51200',
            'cropped_image'    => 'nullable|image|max:2048',

            // --- Datos Personales ---
            'name'        => 'required|string|max:255',
            'lastname'    => 'required|string|max:255',
            'gender'      => 'required|string|in:Masculino,Femenino',
            'state_civil' => 'required|string|in:Casado(a),Soltero(a),Viudo(a),Divorciado(a),Conviviente',
            'birthdate'   => 'required|date|before_or_equal:' . Date::now()->subYears(18)->format('Y-m-d'),
            
            // NOTA: DNI, Email, Phone y NroCuenta se gestionan en los hijos por el "unique"

            // --- Contrato y Costos ---
            'cost_line_id'           => 'required|numeric',
            'type_contract'          => 'required|string',
            'cuspp'                  => 'required_unless:pension_type,ONP|max:12',
            'state_travel_expenses'  => 'required|boolean',
            'amount_travel_expenses' => 'nullable|numeric|required_if:state_travel_expenses,true',
            'discount_remuneration'  => 'required|boolean',
            'discount_sctr'          => 'required|boolean',
            'pension_type'           => 'required|string',
            'basic_salary'           => 'required|numeric',
            'life_ley'               => 'required|boolean',
            'hire_date'              => 'required|date',

            // --- Educación ---
            'education_level'  => 'required|string|in:Universidad,Instituto,Otros',
            'education_status' => 'required|string|in:Incompleto,Completo,En Progreso',
            'specialization'   => 'required|string|max:255',

            // --- Dirección ---
            'street_address' => 'required|string|max:255',
            'department'     => 'required|string|max:255',
            'province'       => 'required|string|max:255',
            'district'       => 'required|string|max:255',

            // --- Contactos de Emergencia (Arrays) ---
            'emergencyContacts.*.emergency_name'      => 'required|string|max:255',
            'emergencyContacts.*.emergency_lastname'  => 'required|string|max:255',
            'emergencyContacts.*.emergency_relations' => 'required|string|max:255',
            'emergencyContacts.*.emergency_phone'     => 'required|numeric|digits:9',

            // --- Dependientes (Arrays) ---
            'familyDependents.*.family_dni'       => 'nullable|numeric|digits:8',
            'familyDependents.*.family_education' => 'required|string|in:Universidad,Instituto,Secundaria,Primaria,Inicial,Otros',
            'familyDependents.*.family_relation'  => 'required|string|max:255',
            'familyDependents.*.family_name'      => 'required|string|max:255',
            'familyDependents.*.family_lastname'  => 'required|string|max:255',

            // --- Salud y Físico ---
            'blood_group'       => 'nullable|string|in:A+,A-,B+,B-,AB-,AB+,O+,O-,RH+',
            'weight'            => 'nullable|numeric',
            'height'            => 'nullable|numeric',
            'shoe_size'         => 'nullable|numeric',
            'shirt_size'        => 'nullable|string',
            'pants_size'        => 'nullable|numeric',
            'medical_condition' => 'required|string|max:255',
            'allergies'         => 'required|string|max:255',
            'operations'        => 'required|string|max:255',
            'accidents'         => 'required|string|max:255',
            'vaccinations'      => 'required|string|max:255',
        ];
    }
}