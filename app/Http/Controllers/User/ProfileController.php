<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest\ProfileUpdateRequest;
use Src\User\Application\Services\UserService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    //public function allFine()
    //{
    //    $prodData = ToolsGtd::all();
//
    //    foreach ($prodData as $index => $item) {
    //        $unit = Unit::where('name', $item->unit)->first();
    //        if (!$unit)
    //            throw new \Exception("Unidad no encontrada: " . $item->unit);
    //        Product::create([
    //            'name' => mb_strtoupper($item->name, 'UTF-8'),
    //            'description' => mb_strtoupper($item->name, 'UTF-8'),
    //            'primary_code' => $index,
    //            'secondary_code' => implode(
    //                '-',
    //                array_filter([
    //                    $item->code_ax ?? null,
    //                    $item->internal_reference ?? null,
    //                ])
    //            ),
    //            'sc_type' => 'CICSA',
    //            'unit_id' => $unit->id,
    //        ]);
    //    }
//
    //    return response()->json([
    //        'status' => 'success',
    //        'message' => 'Productos creados'
    //    ]);
    //}

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    //public function me(Request $request)
    //{
    //    return response()->json($request->user());
    //}


    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request , int $id): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        if ($user->id !== $id) {
            return response()->json([
                'message' => 'Solo puedes actualizar tu propio perfil.'
            ], 403);
        }

        $user->update($request->validated());
        return response()->json([
            'message' => 'Perfil actualizado correctamente.',
            'user' => [
                'name' => $user->name,
                'email' => $user->email
            ]
        ], 200);
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $user->currentAccessToken()->delete();
        $user->delete();
        return response()->json(['message' => 'Account deleted successfully'], 200);
    }
}
