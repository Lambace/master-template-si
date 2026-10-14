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
        public function handle(Request $request, Closure $next, string $role): Response
        {
            // Cek: Apakah user sudah login? Apakah punya role? Apakah rolenya sesuai yang diminta?
            if (!$request->user() || !$request->user()->role || $request->user()->role->nama_role !== $role) {
                // Jika tidak sesuai, tendang keluar dengan error 403 (Forbidden)
                abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk halaman ini.');
            }

            return $next($request);
        }

}
