<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Employees;

use Src\HumanResource\Domain\Entities\Employees\FamilyDependent;
use Illuminate\Database\Eloquent\Collection;

interface FamilyDependentRepositoryInterface
{
    public function find(int $id): ?FamilyDependent;
    public function findByEmployeeId(int $employeeId): Collection;
    public function save(FamilyDependent $dependent): FamilyDependent;
    public function update(FamilyDependent $dependent): FamilyDependent;
    public function deleteByEmployeeId(int $employeeId): void;
    public function saveMultiple(array $dependents): void;
}
