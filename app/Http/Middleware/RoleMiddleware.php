<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $userRoles = auth()->user()->roles()->pluck('name')->toArray(); // Get the role names as an array
        if (!array_intersect($userRoles, $roles)) { // Check if there is any intersection
            return redirect('/')->with('error', 'You do not have access to this resource.');
        }

        return $next($request);
    }
}
