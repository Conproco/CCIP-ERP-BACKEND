<?php

declare(strict_types=1);

namespace App\Http\Requests\HumanResource\ExternalEmployer;

use App\Models\ExternalEmployee;

class StoreExternalEmployeeRequest extends BaseExternalEmployeeRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge($this->baseRules(), [
            'email' => 'required|email|max:255|unique:' . ExternalEmployee::class,
            'email_company' => 'nullable|email|max:255|unique:' . ExternalEmployee::class,
        ]);
    }
}
