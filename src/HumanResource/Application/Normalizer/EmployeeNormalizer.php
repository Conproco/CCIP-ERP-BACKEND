<?php
namespace Src\HumanResource\Application\Normalizer;

use Src\HumanResource\Application\Dto\EmployeeDto;

class EmployeeNormalizer
{
    public function supports($items): bool
    {
        return is_iterable($items) && count($items) > 0 && isset($items[0]->id);
    }

    public function normalize($items): array
    {
        $result = [];
        foreach ($items as $employee) {
            $result[] = new EmployeeDto([
                'id' => $employee->id,
                'name' => $employee->name,
                'lastname' => $employee->lastname,
                'full_name' => $employee->name . ' ' . $employee->lastname,
                'dni' => $employee->dni,
                'email' => $employee->email,
                'phone1' => $employee->phone1,
                'state' => $employee->state,
                'cropped_image' => $employee->cropped_image ?? null,
                'cost_line_id' => $employee->cost_line_id ?? null,
                'cost_line' => $employee->cost_line ? [
                    'id' => $employee->cost_line->id,
                    'name' => $employee->cost_line->name
                ] : null,
            ]);
        }
        return $result;
    }
}
