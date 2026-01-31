<?php

declare(strict_types=1);

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollDeductionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason' => 'required|string',
            'deposit_voucher' => 'required|file',
            'operation_number' => 'required|string',
            'operation_date' => 'required|date',
            'authorization_file' => 'required|file',
            'observations' => 'required|string',
            'employee_id' => 'required|numeric',
            'installments_quantity' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'El motivo es requerido',
            'deposit_voucher.required' => 'El voucher de depósito es requerido',
            'operation_number.required' => 'El número de operación es requerido',
            'operation_date.required' => 'La fecha de operación es requerida',
            'authorization_file.required' => 'El archivo de autorización es requerido',
            'observations.required' => 'Las observaciones son requeridas',
            'employee_id.required' => 'El empleado es requerido',
            'installments_quantity.required' => 'La cantidad de cuotas es requerida',
            'amount.required' => 'El monto es requerido',
            'start_date.required' => 'La fecha de inicio es requerida',
        ];
    }
}
