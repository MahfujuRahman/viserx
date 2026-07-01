<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

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

        return $this->respondWithToken($user, 'User created successfully', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!auth('api')->attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = auth('api')->user();

        return $this->respondWithToken($user, 'Login successful');
    }

    public function me(): JsonResponse
    {
        return response()->json([
            'data' => new UserResource(auth('api')->user()),
        ]);
    }

    public function refresh(Request $request): JsonResponse
    {
        try {
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json(['error' => 'Refresh token missing'], 401);
            }

            $payload = auth('api')->setToken($token)->getPayload();

            if (!$payload->get('is_refresh_token')) {
                return response()->json(['error' => 'Invalid refresh token'], 401);
            }

            $user = User::find($payload->get('sub'));

            if (!$user) {
                return response()->json(['error' => 'Could not refresh token, login again.'], 401);
            }

            return $this->respondWithToken($user, 'Token refreshed successfully');
        } catch (JWTException $e) {
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
    protected function respondWithToken(User $user, string $message, int $status = 200): JsonResponse
    {
        $accessToken = auth('api')->login($user);
        $accessTokenTtl = auth('api')->factory()->getTTL() * 60;

        $refreshToken = auth('api')
            ->claims(['is_refresh_token' => true])
            ->setTTL((int) config('jwt.refresh_ttl', 20160))
            ->login($user);

        $response = [
            'message' => $message,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => $accessTokenTtl,
            'user' => new UserResource($user),
        ];

        return response()->json($response, $status);
    }
}
