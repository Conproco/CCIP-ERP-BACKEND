<?php

declare(strict_types=1);

namespace App\Http\Requests\HumanResource\GrupalDocuments;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGrupalDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string',
            'archive' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($this->hasFile($attribute)) {
                        $file = $this->file($attribute);
                        $extension = $file->getClientOriginalExtension();
                        if (!in_array($extension, ['pdf', 'xls', 'xlsx'])) {
                            $fail('El archivo debe ser un PDF o un archivo de Excel (xls, xlsx).');
                        }
                    }
                },
            ],
            'date' => 'required|date',
            'observation' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'El tipo es requerido',
            'date.required' => 'La fecha es requerida',
        ];
    }
}
