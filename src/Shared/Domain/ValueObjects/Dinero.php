<?php

namespace Src\Shared\Domain\ValueObjects;

use Src\Shared\Domain\Exceptions\InvalidMoneyException;

class Dinero
{
    private float $value;

    public function __construct(float $value)
    {
        if ($value < 0) {
            throw new InvalidMoneyException();
        }
        $this->value = $value;
    }

    public function value(): float
    {
        return $this->value;
    }
}
