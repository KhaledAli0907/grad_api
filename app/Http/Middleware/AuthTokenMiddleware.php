<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Extract token from header
        $authHeader = $request->header('Authorization');
        $token = $authHeader && explode(' ', $authHeader)[1];

        // If there is no token
        if (!$token) {
            return response()->json(['message' => 'Token can not be retrieved'], 401);
        }

        try {
            // Verify token using jwt
            $user = JWTAuth::decode($token, env('ACCESS_TOKEN_SECRET'), ['HS256']);
            $request->merge(['user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
