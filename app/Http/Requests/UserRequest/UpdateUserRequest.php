<?php

namespace App\Http\Requests\UserRequest;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('id');

        $rules = [
            'name' =>  [
                'sometimes',
                'required', 
                'string',
                'max:255',
                Rule::unique('users', 'name')->ignore($userId)
            ],
            'dni' => [
                'sometimes',
                'required',
                'string',
                'max:8',
                Rule::unique('users', 'dni')->ignore($userId)
            ],
            'email' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                'email',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'platform' => 'sometimes|required|string|in:Movil,Web,Web/Movil',
            'phone' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('users', 'phone')->ignore($userId)
            ],
            'role_id' => 'nullable|numeric',
            'area_id' => 'nullable|numeric',
        ];

        $platform = $this->input('platform');

        if ($platform && $platform !== 'Movil') {
            $rules['area_id'] = 'required|numeric';
            $rules['role_id'] = 'required|numeric';
        }

        return $rules;
    }
}
