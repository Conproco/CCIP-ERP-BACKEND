<?php

namespace App\Http\Requests\HumanResource\Payroll;

use Illuminate\Foundation\Http\FormRequest;
use Src\HumanResource\Domain\Enums\Payroll\PayrollPensionType;

class GetPayrollDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'selectedPensionTypes' => ['nullable', 'array'],
            'selectedPensionTypes.*' => ['string', 'in:' . implode(',', PayrollPensionType::values())],
            'selectedStateTypes' => ['nullable', 'array'],
            'selectedStateTypes.*' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'selectedPensionTypes.*.in' => 'El tipo de pensión seleccionado no es válido.',
        ];
    }
}
