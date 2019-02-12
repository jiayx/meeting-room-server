<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (! $user) {
                return response()->json([
                    'code' => '401',
                    'message' => 'Unauthenticated.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'code' => '401',
                'message' => 'Unauthenticated.',
            ]);
        }

        return $next($request);
    }
}
