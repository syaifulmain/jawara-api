<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Login user
     *
     * @param LoginRequest $request - Validasi otomatis
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->errorResponse('Email atau password salah', 401);
            }

            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successResponse([
                'user' => new UserResource($user),
                'token' => $token,
            ], 'Login berhasil');
        } catch (Exception $e) {
            return $this->errorResponse('Terjadi kesalahan saat login', 500);
        }
    }

    /**
     * Logout user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return $this->successResponse(null, 'Logout berhasil');
        } catch (Exception $e) {
            return $this->errorResponse('Terjadi kesalahan saat logout', 500);
        }
    }
}
