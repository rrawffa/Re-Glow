<?php

// File: app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah user memiliki role yang sesuai
        if (!Session::has('user_role')) {
            return redirect()->route('login')->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        $userRole = Session::get('user_role');

        // Cek apakah role user ada dalam list roles yang diizinkan
        if (!in_array($userRole, $roles)) {
            // Redirect ke dashboard sesuai role user
            switch ($userRole) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini']);
                case 'tim_logistik':
                    return redirect()->route('logistik.dashboard')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini']);
                case 'pengguna':
                default:
                    return redirect()->route('user.dashboard')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini']);
            }
        }

        return $next($request);
    }
}