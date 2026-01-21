<?php

namespace App\Http\Requests\HumanResource\Employees;

use App\Models\Contract;
use App\Models\Employee;

class CreateManagementEmployees extends BaseEmployeeRequest
{
    public function rules(): array
    {
        // 1. Obtenemos las reglas del padre
        $rules = parent::rules();

        // 2. Agregamos las reglas específicas para CREAR (Unique estricto)
        return array_merge($rules, [
            'dni'           => 'required|numeric|digits:8|unique:' . Employee::class,
            'email'         => 'required|email|max:255|unique:' . Employee::class,
            'email_company' => 'nullable|email|max:255|unique:' . Employee::class,
            'phone1'        => 'required|numeric|digits:9|unique:' . Employee::class,
            'nro_cuenta'    => 'nullable|unique:' . Contract::class,
        ]);
    }
}