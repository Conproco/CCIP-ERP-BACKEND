<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Employees;

use Src\HumanResource\Domain\Entities\Employees\Health;

interface HealthRepositoryInterface
{
    public function find(int $id): ?Health;
    
    public function findByEmployeeId(int $employeeId): ?Health;
    
    public function save(Health $health): Health;
    
    public function update(int $employeeId, array $data): bool;
    
    public function delete(int $id): void;
}
