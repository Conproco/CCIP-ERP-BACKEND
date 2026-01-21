<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Employees;

use Src\HumanResource\Domain\Entities\Employees\Address;

interface AddressRepositoryInterface
{
    public function find(int $id): ?Address;
    
    public function findByEmployeeId(int $employeeId): ?Address;
    
    public function save(Address $address): Address;
    
    public function update(int $employeeId, array $data): bool;
    
    public function delete(int $id): void;
}
