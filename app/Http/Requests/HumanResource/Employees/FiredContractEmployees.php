<?php

namespace App\Http\Requests\HumanResource\Employees;

use Illuminate\Foundation\Http\FormRequest;

class FiredContractEmployees extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fired_date' => 'required|date',
            'days_taken' => 'required|integer|min:0',
            'discharge_document' => 'nullable|file|max:10240',
            'state' => 'sometimes|string|in:Fired,Inactive',
        ];
    }
}
