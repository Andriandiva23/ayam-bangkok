<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah sudah login
        if (!auth()->check()) {
            abort(403);
        }

        // Cek role user
        if (auth()->user()->role != $role) {
            abort(403);
        }

        return $next($request);
    }
}