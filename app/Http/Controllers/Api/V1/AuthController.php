<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\ResponseResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

                return new ResponseResource(true, 'Registration Success', [
                    'token' => $token, 
                    'user' => UserResource::make($user)
                ]);
            });
        } catch (Exception $e) {
            report($e);

            return (new ResponseResource(false, 'Registration Failed', $e->getMessage()))
                ->response()
                ->setStatusCode(500);
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return (new ResponseResource(false, 'Login Failed', ''))
                ->response()
                ->setStatusCode(401);
        }

        $payload = JWTAuth::setToken($token)->getPayload();
        $expiresAt = Carbon::createFromTimestamp($payload->get('exp'), config('app.timezone'));

        return (new ResponseResource(true, 'Login Success', [
            'token' => $token,
            'token_type'   => 'bearer',
            'expires_at'   => $expiresAt->toDateTimeString(),
        ]));
    }

    public function me()
    {
        $user = Auth::user();
        return new ResponseResource(true, 'User Info', UserResource::make($user));
    }
}
