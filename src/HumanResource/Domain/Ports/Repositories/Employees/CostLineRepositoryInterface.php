<?php

namespace Src\HumanResource\Domain\Ports\Repositories\Employees;

use Illuminate\Database\Eloquent\Collection;

interface CostLineRepositoryInterface
{
    public function find(int $id): ?object;
    
    public function getAll(): Collection;
    
    public function findByName(string $name): ?object;
}
