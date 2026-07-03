<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Ambil role user dari database
        // Gunakan strtolower (huruf kecil) dan trim (hapus spasi ekstra) agar aman
        $userRole = strtolower(trim(Auth::user()->role));

        // 3. Pastikan role target dari routes juga huruf kecil dan dukung format string dipisah koma
        $rolesArray = [];
        foreach ($roles as $r) {
            $rolesArray = array_merge($rolesArray, explode(',', $r));
        }
        $allowedRoles = array_map('strtolower', array_map('trim', $rolesArray));

        // 4. Jika role user ada di dalam daftar yang diizinkan, silakan masuk
        if (in_array($userRole, $allowedRoles)) {
            return $next($request);
        }

        // 5. Jika tidak cocok, tolak akses (403 Forbidden)
        abort(403, 'FORBIDDEN');
    }
}