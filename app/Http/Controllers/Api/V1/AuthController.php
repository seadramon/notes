<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $payload = $request->validated();
            return DB::transaction(function () use ($payload) {
                $user = User::create([
                    'name'     => $payload['name'],
                    'email'    => $payload['email'],
                    'password' => Hash::make($payload['password']),
                ]);

                $token = JWTAuth::fromUser($user);

                return response()->json([
                    'token' => $token, 
                    'user' => UserResource::make($user)
                ], 201);
            });
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'error'   => 'Registration failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function me()
    {
        return response()->json(UserResource::make(auth()->user()));
    }
}
