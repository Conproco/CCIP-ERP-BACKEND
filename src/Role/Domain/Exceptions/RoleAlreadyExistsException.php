<?php

namespace Src\Role\Domain\Exceptions;

use Exception;

class RoleAlreadyExistsException extends Exception
{
    public function __construct(string $field, string $value)
    {
        parent::__construct("Ya existe un rol con {$field}: {$value}", 422);
    }
}
