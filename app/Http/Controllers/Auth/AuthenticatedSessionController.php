<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Src\User\Application\Services\UserACLService;
class AuthenticatedSessionController extends Controller
{

    public function __construct(
        protected UserACLService $userACLService
    ) {}


    public function store(LoginRequest $request): JsonResponse{
        try {
            $request->authenticate();

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;
        $permissionsTree = $this->userACLService->getPermissionsTree($user);
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'access' => $permissionsTree
        ], 200);
    } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error en el servidor',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Logout successful'
        ], 200);
    }

    public function me(Request $request): JsonResponse
    {
        $permissionsTree = $this->userACLService->getPermissionsTree($request->user());
        return response()->json($permissionsTree, 200);
    }
}







