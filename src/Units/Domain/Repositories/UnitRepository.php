<?php

namespace Src\Units\Domain\Repositories;

use Src\Units\Domain\Entities\UnitEntity;

interface UnitRepository
{
    public function find(int $id): ?UnitEntity;
    public function all(): array;
}
