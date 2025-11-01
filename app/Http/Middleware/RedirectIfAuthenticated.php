<?php

// File: app/Http/Middleware/RedirectIfAuthenticated.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // Jika user sudah login, redirect ke dashboard sesuai role
        if (Session::has('user_id')) {
            $role = Session::get('user_role');
            
            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'tim_logistik':
                    return redirect()->route('logistik.dashboard');
                case 'pengguna':
                default:
                    return redirect()->route('user.dashboard');
            }
        }

        return $next($request);
    }
}