<?php

declare(strict_types=1);

namespace App\Http\Requests\HumanResource\ExternalEmployer;

use App\Models\ExternalEmployee;
use Illuminate\Validation\Rule;

class UpdateExternalEmployeeRequest extends BaseExternalEmployeeRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $external_id = $this->route('external_id');
        
        return array_merge($this->baseRules(), [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(ExternalEmployee::class)->ignore($external_id),
            ],
            'email_company' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique(ExternalEmployee::class)->ignore($external_id),
            ],
        ]);
    }
}
