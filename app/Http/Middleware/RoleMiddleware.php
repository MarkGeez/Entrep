<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
     public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
 
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
 
        if ($user->status !== 'active') {
            return response()->json(['message' => 'Your account is suspended.'], 403);
        }
 
        if (! in_array($user->role, $roles, true)) {
            return response()->json(['message' => 'You do not have access to this resource.'], 403);
        }
 
        return $next($request);
    }
}
