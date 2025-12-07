<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        // If user not authenticated, let the auth middleware handle it earlier in the stack.
        if (!$user) {
            return redirect()->route('login');
        }

        // Allow if the user role matches any of the allowed roles.
        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        throw new AccessDeniedHttpException('This action is unauthorized.');
    }
}
