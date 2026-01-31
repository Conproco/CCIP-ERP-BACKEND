<?php

declare(strict_types=1);

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class MassiveUpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:payroll_detail_expenses,id',
            'operation_date' => 'required|date',
            'operation_number' => 'required|digits:6',
        ];
    }

    public function messages(): array
    {
        return [
            'ids.required' => 'Los IDs son requeridos',
            'ids.min' => 'Debe seleccionar al menos un registro',
            'operation_date.required' => 'La fecha de operación es requerida',
            'operation_number.required' => 'El número de operación es requerido',
            'operation_number.digits' => 'El número de operación debe tener 6 dígitos',
        ];
    }
}
