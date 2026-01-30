<?php

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'month' => 'required|string|unique:payrolls,month',
            'state' => 'required|boolean',
            'pension_system' => 'required|array|min:1',
            'pension_system.*.type' => 'required|string',
            'pension_system.*.commission_flow' => 'required|numeric',
            'pension_system.*.annual_commission_balance' => 'required|numeric',
            'pension_system.*.insurance_premium' => 'required|numeric',
            'pension_system.*.mandatory_contribution' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'month.required' => 'El mes es requerido',
            'month.unique' => 'Ya existe una nómina para este mes',
            'pension_system.required' => 'Se requiere al menos un sistema de pensión',
        ];
    }
}
