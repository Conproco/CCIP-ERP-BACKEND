<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Employees;

use Src\HumanResource\Domain\Entities\Employees\Employee;
use Illuminate\Database\Eloquent\Collection;

interface EmployeeRepositoryInterface
{
    public function find(int $id): ?Employee;
    public function findWithRelations(int $id): ?object;
    public function save(Employee $employee): Employee;
    public function update(Employee $employee): Employee;
    public function delete(int $id): void;
    public function getAll(): Collection;
    public function getActiveEmployees(): Collection;
    public function getEmployeesByState(string $state, bool $paginate = true, int $perPage = 15): mixed;
    public function search(?string $state, ?string $searchTerm, ?array $costLines): Collection;
    public function getBirthdaysInRange(\DateTime $startDate, \DateTime $endDate): Collection;
}
