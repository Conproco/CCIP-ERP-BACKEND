<?php

declare(strict_types=1);

namespace App\Http\Requests\HumanResource\GrupalDocuments;

use Illuminate\Foundation\Http\FormRequest;

class StoreGrupalDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string',
            'archive' => 'required|mimes:pdf,xlsx,xls',
            'date' => 'required|date',
            'observation' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'El tipo es requerido',
            'archive.required' => 'El archivo es requerido',
            'archive.mimes' => 'El archivo debe ser PDF o Excel (xlsx, xls)',
            'date.required' => 'La fecha es requerida',
        ];
    }
}
