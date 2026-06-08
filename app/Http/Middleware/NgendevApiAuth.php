<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NgendevApiAuth
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized. Authorization header with Bearer token is required.',
                'data'    => [],
            ], 401);
        }

        $token = substr($authHeader, 7);

        if ($token !== env('NGD_API_TOKEN')) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized. Invalid API token.',
                'data'    => [],
            ], 401);
        }

        return $next($request);
    }
}
