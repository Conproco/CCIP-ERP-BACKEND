<?php

namespace Src\Role\Domain\Exceptions;

use Exception;

class RoleNotFoundException extends Exception
{
    public function __construct(int $roleId)
    {
        parent::__construct("Rol con ID {$roleId} no encontrado", 404);
    }
}
