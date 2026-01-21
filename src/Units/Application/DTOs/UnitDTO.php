<?php 


namespace Src\Units\Application\DTOs;
use App\Models\Unit;


final class UnitDTO
{
    public function __construct(
        public int $id,
        public string $name
    ) {}

    public static function fromEntity(Unit $unit): self
    {
        return new self(
            $unit->id,
            $unit->name
        );
    }
}
