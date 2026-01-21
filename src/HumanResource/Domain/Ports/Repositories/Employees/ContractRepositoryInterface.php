<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Employees;

use Src\HumanResource\Domain\Entities\Employees\Contract;

interface ContractRepositoryInterface
{
    public function find(int $id): ?Contract;
    
    public function findByEmployeeId(int $employeeId): ?Contract;
    
    public function save(Contract $contract): Contract;
    
    public function update(int $id, array $data): bool;
    
    public function delete(int $id): void;
}
