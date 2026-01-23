<?php

declare(strict_types=1);

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayrollDeductionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => 'required|string',
            'operation_number' => 'required|string',
            'operation_date' => 'required|date',
            'observations' => 'required|string',
            'employee_id' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'El motivo es requerido',
            'operation_number.required' => 'El número de operación es requerido',
            'operation_date.required' => 'La fecha de operación es requerida',
            'observations.required' => 'Las observaciones son requeridas',
            'employee_id.required' => 'El empleado es requerido',
        ];
    }
}
