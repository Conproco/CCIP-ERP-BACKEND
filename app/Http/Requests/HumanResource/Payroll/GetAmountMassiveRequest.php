<?php

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;
use Src\HumanResource\Domain\Enums\Payroll\PayrollExpenseType;
use Illuminate\Validation\Rule;

class GetAmountMassiveRequest extends FormRequest
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
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:payroll_details,id'],
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
            'ids.required' => 'Los IDs de detalles de planilla son requeridos',
            'ids.array' => 'Los IDs deben ser un array',
            'ids.min' => 'Debe proporcionar al menos un ID',
            'ids.*.integer' => 'Cada ID debe ser un número entero',
            'ids.*.exists' => 'Uno o más detalles de planilla no existen',
            'type.required' => 'El tipo de gasto es requerido',
            'type.enum' => 'El tipo de gasto no es válido',
        ];
    }

    /**
     * Prepare the data for validation.
     * Legacy behavior: GET request with data in body
     */
    protected function prepareForValidation(): void
    {
        // Legacy: reads from body even on GET request
        // Using input() which reads from both query and body
        $ids = $this->input('ids');

        // If ids is a comma-separated string, convert to array
        if (is_string($ids)) {
            $ids = array_map('intval', explode(',', $ids));
        }

        $this->merge([
            'ids' => $ids,
            'type' => $this->input('type'),
        ]);
    }
}
