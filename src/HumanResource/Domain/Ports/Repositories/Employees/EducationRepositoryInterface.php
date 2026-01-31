<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Employees;

use Src\HumanResource\Domain\Entities\Employees\Education;

interface EducationRepositoryInterface
{
    public function find(int $id): ?Education;
    
    public function findByEmployeeId(int $employeeId): ?Education;
    
    public function save(Education $education): Education;
    
    public function update(int $employeeId, array $data): bool;
    
    public function delete(int $id): void;
}
