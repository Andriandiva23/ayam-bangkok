<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDirectUrlAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya cek untuk request GET dan bukan ajax
        if ($request->isMethod('get') && !$request->ajax()) {
            $referer = $request->headers->get('referer');
            $host = $request->getHost();

            // Jika tidak ada referer sama sekali (URL diketik manual, copy-paste, atau tekan Enter di URL bar)
            if (empty($referer)) {
                abort(403, 'Akses Ditolak: Anda tidak diizinkan mengubah atau mengetik URL secara manual. Silakan gunakan menu navigasi yang tersedia.');
            }

            // Jika referer ada tapi dari website luar
            $refererHost = parse_url($referer, PHP_URL_HOST);
            if ($refererHost !== $host) {
                abort(403, 'Akses Ditolak: Referer tidak valid.');
            }
        }

        return $next($request);
    }
}
