<?php

// File: app/Http/Middleware/CheckSession.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login
        if (!Session::has('user_id')) {
            return redirect()->route('login')->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        $sessionId = Session::get('session_id');
        
        // Cek session di database
        $dbSession = DB::table('session')
            ->where('session_id', $sessionId)
            ->first();

        // Validasi session
        if (!$dbSession || now()->greaterThan($dbSession->waktu_expire)) {
            Session::flush();
            return redirect()->route('login')->withErrors(['error' => 'Session Anda telah berakhir. Silakan login kembali.']);
        }

        return $next($request);
    }
}
