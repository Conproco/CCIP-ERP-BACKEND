<?php

namespace App\Http\Requests\UserRequest;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'name')
            ],
            'dni' => [
                'required',
                'string',
                'max:8',
                Rule::unique('users', 'dni')
            ],
            'email' => [
                'required',
                'string',
                'max:255',
                'email',
                Rule::unique('users', 'email')
            ],
            'platform' => 'required|string|in:Movil,Web,Web/Movil',
            'phone' => [
                'required',
                'string',
                Rule::unique('users', 'phone')
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['nullable', 'integer'], 
            'area_id' => ['nullable', 'integer'], 
        ];

        if ($this->input('company') === 'CCIP') {
            if ($this->input('platform') !== 'Movil'){
                $rules['area_id'] = 'required';
            }
        }

        if ($this->input('platform') !== 'Movil'){
            $rules['role_id'] = 'required';
            $rules['area_id'] = 'required';
        }

        return $rules;
    }
}
