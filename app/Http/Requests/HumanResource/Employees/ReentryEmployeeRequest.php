<?php

namespace App\Http\Requests\HumanResource\Employees;

use Illuminate\Foundation\Http\FormRequest;

class ReentryEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reentry_date' => 'required|date',
        ];
    }
}
