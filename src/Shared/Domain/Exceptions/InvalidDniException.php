<?php
namespace Src\Shared\Domain\Exceptions;

use Exception;

class InvalidDniException extends Exception
{
	public function __construct(string $message = "El DNI proporcionado no es válido.", int $code = 422)
	{
		parent::__construct($message, $code);
	}
}
