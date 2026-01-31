<?php

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;
use Src\HumanResource\Domain\Enums\Payroll\PayrollExpenseType;
use Illuminate\Validation\Rule;

class GetAmountByExpenseTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payroll_detail_id' => ['required', 'integer', 'exists:payroll_details,id'],
            'type' => ['required', 'string', Rule::enum(PayrollExpenseType::class)],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'payroll_detail_id.required' => 'El ID del detalle de planilla es requerido',
            'payroll_detail_id.integer' => 'El ID del detalle de planilla debe ser un número entero',
            'payroll_detail_id.exists' => 'El detalle de planilla no existe',
            'type.required' => 'El tipo de gasto es requerido',
            'type.enum' => 'El tipo de gasto no es válido',
        ];
    }

    /**
     * Prepare the data for validation.
     * This method is called before validation to transform query parameters.
     */
    protected function prepareForValidation(): void
    {
        // Merge query parameters into the request for validation
        $this->merge([
            'payroll_detail_id' => $this->query('payroll_detail_id'),
            'type' => $this->query('type'),
        ]);
    }
}
