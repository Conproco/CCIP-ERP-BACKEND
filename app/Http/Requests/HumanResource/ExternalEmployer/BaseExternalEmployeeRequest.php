<?php

declare(strict_types=1);

namespace App\Http\Requests\HumanResource\ExternalEmployer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

abstract class BaseExternalEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the base validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    protected function baseRules(): array
    {
        $maxAgeDate = Date::now()->subYears(18)->format('Y-m-d');
        $minAgeDate = Date::now()->subYears(100)->format('Y-m-d');
        return [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'cost_line_id' => 'required|numeric|exists:cost_lines,id',
            'cropped_image' => 'nullable',
            'gender' => 'required|in:Masculino,Femenino',
            'address' => 'required|string|max:500',
            'birthdate' => 'required|date|before_or_equal:' . $maxAgeDate . '|after_or_equal:' . $minAgeDate,
            'dni' => 'required|numeric|digits:8',
            'phone1' => 'required|numeric|digits:9',
            'salary' => 'required|numeric|min:0',
            'sctr' => 'required|boolean',
            'curriculum_vitae' => 'nullable|max:5120',
            'l_policy' => 'nullable|max:5120',
            'sctr_exp_date' => 'nullable|date',
            'policy_exp_date' => 'nullable|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'lastname.required' => 'El apellido es obligatorio.',
            'cost_line_id.required' => 'La línea de costo es obligatoria.',
            'cost_line_id.exists' => 'La línea de costo seleccionada no existe.',
            'gender.required' => 'El género es obligatorio.',
            'gender.in' => 'El género debe ser Masculino o Femenino.',
            'address.required' => 'La dirección es obligatoria.',
            'birthdate.required' => 'La fecha de nacimiento es obligatoria.',
            'birthdate.before_or_equal' => 'El empleado debe ser mayor de 18 años.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.digits' => 'El DNI debe tener 8 dígitos.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'email_company.unique' => 'Este correo corporativo ya está registrado.',
            'phone1.required' => 'El teléfono es obligatorio.',
            'phone1.digits' => 'El teléfono debe tener 9 dígitos.',
            'salary.required' => 'El salario es obligatorio.',
            'salary.min' => 'El salario debe ser mayor o igual a 0.',
            'sctr.required' => 'El campo SCTR es obligatorio.',
        ];
    }
}
