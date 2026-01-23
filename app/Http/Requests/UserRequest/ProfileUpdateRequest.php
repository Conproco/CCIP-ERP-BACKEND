<?php


namespace App\Http\Requests\UserRequest;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['sometimes','required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'string', 'max:255', Rule::unique('users')->ignore(Auth::user())],

        ];
    }

    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->password == null) {
            $this->request->remove('password');
        }
    }
}
