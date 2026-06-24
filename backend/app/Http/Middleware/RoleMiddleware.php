<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request by verifying if the user has the required roles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = auth()->user();

        // 1. Check if the user is authenticated and has one of the allowed roles
        if (!$user || !in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'Access denied. Required role: ' . implode(' or ', $roles),
            ], 403); // HTTP 403 Forbidden
        }

        return $next($request);
    }
}
