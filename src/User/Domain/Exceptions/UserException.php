<?php

namespace Src\User\Domain\Exceptions;

class UserException extends \Exception {}

class UserNotFoundException extends UserException
{
    public function __construct(int $id)
    {
        parent::__construct("Usuario no encontrado con ID: {$id}");
    }
}

class UserAlreadyExistsException extends UserException
{
    public function __construct(string $field, string $value)
    {
        parent::__construct("Ya existe un usuario con {$field}: {$value}");
    }
}

class UserDeletionException extends UserException
{
    public function __construct(string $reason = '')
    {
        $message = 'No se puede eliminar el usuario';
        if ($reason) {
            $message .= ': ' . $reason;
        }
        parent::__construct($message);
    }
}
