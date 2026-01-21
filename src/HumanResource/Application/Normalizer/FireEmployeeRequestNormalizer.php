<?php

namespace Src\HumanResource\Application\Normalizer;

use Src\HumanResource\Application\Dto\FireEmployeeDto;
use App\Http\Requests\HumanResource\Employees\FiredContractEmployees;

/**
 * Normaliza el request de despido de empleado a un DTO.
 */
class FireEmployeeRequestNormalizer
{
    public function normalize(FiredContractEmployees $request, int $employeeId): FireEmployeeDto
    {
        return new FireEmployeeDto(
            employeeId: $employeeId,
            firedDate: $request->input('fired_date'),
            daysTaken: (int) $request->input('days_taken', 0),
            state: $request->input('state', 'Fired'),
            dischargeDocument: $request->file('discharge_document'),
        );
    }
}
