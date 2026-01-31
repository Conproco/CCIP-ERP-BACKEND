<?php

declare(strict_types=1);

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayrollDetailExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|numeric|exists:payroll_detail_expenses,id',
            'payroll_detail_id' => 'required|numeric|exists:payroll_details,id',
            'general_expense_id' => 'nullable|numeric',
            'employee_name' => 'required|string|max:255',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'expense_type' => 'required|string|max:100',
            'operation_number' => 'nullable|digits:6',
            'operation_date' => 'nullable|date',
            'doc_date' => 'nullable|date',
            'doc_number' => 'nullable|string|max:20',
            'type_doc' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0.01',
        ];
    }

    /**
     * Prepare data for validation - merge route parameter
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

    public function messages(): array
    {
        return [
            'id.required' => 'El ID del gasto es requerido',
            'id.exists' => 'El gasto no existe',
            'payroll_detail_id.required' => 'El detalle de planilla es requerido',
            'employee_name.required' => 'El nombre del empleado es requerido',
            'expense_type.required' => 'El tipo de gasto es requerido',
            'type_doc.required' => 'El tipo de documento es requerido',
            'amount.required' => 'El monto es requerido',
            'amount.min' => 'El monto debe ser mayor a 0',
        ];
    }
}
