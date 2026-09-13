<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Menggunakan variadic parameter (...$roles) untuk mendukung multiple roles
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        // 1. Cek apakah user sudah login
        if (!$user) {
            return redirect()->route('login');
        }

        // 2. Cek apakah role user ada di dalam daftar $roles yang diperbolehkan
        if (!in_array($user->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}