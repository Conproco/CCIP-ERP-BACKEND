<?php

namespace App\Http\Requests\HumanResource\Employees;

use App\Models\Contract;
use App\Models\Employee;
use Illuminate\Validation\Rule;

class UpdateManagementEmployees extends BaseEmployeeRequest
{
    public function rules(): array
    {
        // 1. Obtenemos las reglas del padre
        $rules = parent::rules();

        // 2. Obtenemos el ID del empleado desde la ruta (URL)
        // Asegúrate que tu ruta sea algo como: Route::put('/employees/{id}', ...)
        $employeeId = $this->route('id'); 

        // 3. Buscamos el ID del contrato asociado para ignorarlo en la validación
        // Usamos value('id') para que sea más eficiente (solo trae la columna id)
        $contractId = Contract::where('employee_id', $employeeId)->value('id');

        // 4. Agregamos/Sobreescribimos las reglas para ACTUALIZAR
        return array_merge($rules, [
            // Validamos unique en employees pero ignorando el ID actual
            'dni' => [
                'required', 'numeric', 'digits:8',
                Rule::unique(Employee::class)->ignore($employeeId),
            ],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique(Employee::class)->ignore($employeeId),
            ],
            'email_company' => [
                'nullable', 'email', 'max:255',
                Rule::unique(Employee::class)->ignore($employeeId),
            ],
            'phone1' => [
                'required', 'numeric', 'digits:9',
                Rule::unique(Employee::class)->ignore($employeeId),
            ],
            
            // Validamos unique en contracts ignorando el ID del contrato encontrado
            'nro_cuenta' => [
                'nullable',
                Rule::unique(Contract::class, 'nro_cuenta')->ignore($contractId),
            ],
        ]);
    }
}