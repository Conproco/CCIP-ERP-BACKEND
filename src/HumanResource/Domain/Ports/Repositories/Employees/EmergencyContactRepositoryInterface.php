<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Employees;

use Src\HumanResource\Domain\Entities\Employees\EmergencyContact;
use Illuminate\Database\Eloquent\Collection;

interface EmergencyContactRepositoryInterface
{
    public function find(int $id): ?EmergencyContact;
    public function findByEmployeeId(int $employeeId): Collection;
    public function save(EmergencyContact $contact): EmergencyContact;
    public function update(EmergencyContact $contact): EmergencyContact;
    public function deleteByEmployeeId(int $employeeId): void;
    public function saveMultiple(array $contacts): void;
}
