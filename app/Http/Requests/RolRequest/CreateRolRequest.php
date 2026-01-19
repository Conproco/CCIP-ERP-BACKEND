<?php

namespace App\Http\Requests\RolRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateRolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'functionalities' => 'sometimes|required|array'
        ];
    }
}
