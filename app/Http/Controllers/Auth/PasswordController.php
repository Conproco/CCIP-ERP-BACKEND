<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Src\User\Application\Services\UserService;
class PasswordController extends Controller
{

    public function __construct(
        protected UserService $userService
        
    )
{}

    /**
     * Update the user's password.
     */
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {

        if ($request->user()->id !== $id) {
            return response()->json([
                'message' => 'Solo puedes actualizar tu propia contraseña.'
            ], 403);
        }

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.current_password' => 'La contraseña actual es incorrecta.',
            'password.confirmed' => 'La confirmación de la nueva contraseña no coincide.'
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' => 'Contraseña actualizada correctamente.'], 200);
    }
}
