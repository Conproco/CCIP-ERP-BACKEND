<?php

namespace Src\User\Domain\Exceptions;

class InvalidCredentialsException extends UserException
{
    public function __construct()
    {
        parent::__construct('Credenciales inválidas');
    }
    
}
