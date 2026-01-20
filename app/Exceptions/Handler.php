<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Src\HumanResource\Domain\Exceptions\HumanResourceDomainException;
use Src\Shared\Domain\Exceptions\InvalidDniException;
use Src\Shared\Domain\Exceptions\InvalidEmailException;
use Src\Shared\Domain\Exceptions\InvalidPhoneException;
use Throwable;

class Handler extends ExceptionHandler
{
	/**
	 * The list of the inputs that are never flashed to the session on validation exceptions.
	 *
	 * @var array<int, string>
	 */
	protected $dontFlash = [
		'current_password',
		'password',
		'password_confirmation',
	];

	/**
	 * Register the exception handling callbacks for the application.
	 */
	public function register(): void
	{
		$this->reportable(function (Throwable $e) {
			//
		});
	}

	public function render($request, Throwable $exception)
	{
		// Excepciones de dominio HumanResource
		if ($exception instanceof HumanResourceDomainException) {
			return response()->json([
				'message' => $exception->getMessage(),
				'type' => class_basename($exception),
			], $exception->getStatusCode());
		}

		// Excepciones de Value Objects (Shared Domain)
		if (
			$exception instanceof InvalidDniException ||
			$exception instanceof InvalidEmailException ||
			$exception instanceof InvalidPhoneException
		) {
			return response()->json([
				'message' => $exception->getMessage(),
				'type' => class_basename($exception),
			], 422);
		}

		// Errores de validación
		if ($exception instanceof ValidationException) {
			return response()->json([
				'message' => 'Datos inválidos.',
				'errors' => $exception->errors(),
			], 422);
		}

		// Errores de base de datos
		if ($exception instanceof QueryException) {
			if ($request->expectsJson()) {
				return response()->json([
					'message' => 'Error de base de datos.',
					'error' => config('app.debug') ? $exception->getMessage() : 'Database error',
				], 409);
			}
		}

		// Otros errores para API
		if ($request->expectsJson()) {
			return response()->json([
				'message' => $exception->getMessage(),
				'type' => class_basename($exception),
				'trace' => config('app.debug') ? $exception->getTraceAsString() : null,
			], 500);
		}

		return parent::render($request, $exception);
	}
}