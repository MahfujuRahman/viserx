<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = auth('api')->login($user);

        return $this->respondWithToken($token, 'User created successfully', $user);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!$token = auth('api')->attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return $this->respondWithToken($token, 'Login successful', auth('api')->user());
    }

    public function me(): JsonResponse
    {
        return response()->json([
            'data' => new UserResource(auth('api')->user()),
        ]);
    }

    /**
     * Refresh the access token using the current token / refresh mechanism.
     */
    public function refresh(): JsonResponse
    {
        try {
            $newToken = auth('api')->refresh();

            return $this->respondWithToken($newToken, 'Token refreshed successfully');
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Could not refresh token, login again.'], 401);
        }
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Helper to format the token response structure.
     */
    protected function respondWithToken(string $token, string $message, ?User $user = null): JsonResponse
    {
        // 1. Current Access Token TTL (e.g., 60 minutes)
        $accessTokenTtl = auth('api')->factory()->getTTL() * 60;

        // 2. Create a separate, long-lived Refresh Token (e.g., valid for 2 weeks / 20160 minutes)
        // We add a custom claim 'is_refresh_token' => true to distinguish it from access tokens
        $refreshToken = auth('api')
            ->claims(['is_refresh_token' => true])
            ->setTTL(20160)
            ->login(auth('api')->user() ?? $user);

        $response = [
            'message' => $message,
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => $accessTokenTtl,
        ];

        if ($user) {
            $response['user'] = new UserResource($user);
        }

        return response()->json($response, $user ? 201 : 200);
    }
}
