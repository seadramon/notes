<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            switch (true) {
                case ($e instanceof TokenExpiredException):
                    return response()->json(['message' => 'Token expired'], 401);
                    break;
                case ($e instanceof JWTException):
                    return response()->json(['message' => 'Token invalid'], 401);
                    break;
                default:
                    return response()->json(['message' => 'Token not found'], 401);
                    break;
            }
        }
        return $next($request);
    }
}
