<?php

namespace App\Http\Middleware;
use Firebase\JWT\JWT;
use Closure;
use Illuminate\Http\Request;
 
 

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $decoded = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
