<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        if (Session::has('user_id')) {
            return $this->redirectToDashboard(Session::get('user_role'));
        }
        
        return view('login');
    }

    // Proses login
    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|ends_with:@gmail.com',
            'password' => 'required'
        ], [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.ends_with' => 'Email harus menggunakan @gmail.com',
            'password.required' => 'Password tidak boleh kosong'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah'])->withInput();
        }

        if (!$user->isActive()) {
            return back()->withErrors(['email' => 'Akun Anda tidak aktif'])->withInput();
        }

        $sessionId = Str::random(64);
        $expireTime = now()->addHours(24);

        DB::table('session')->insert([
            'session_id' => $sessionId,
            'id_user' => $user->id_user,
            'waktu_login' => now(),
            'waktu_expire' => $expireTime,
            'ip_address' => $request->ip()
        ]);

        Session::put('user_id', $user->id_user);
        Session::put('username', $user->username);
        Session::put('email', $user->email);
        Session::put('user_role', $user->role);
        Session::put('session_id', $sessionId);

        if ($request->has('remember')) {
            Cookie::queue('remember_email', $request->email, 43200);
            Cookie::queue('remember_password', $request->password, 43200);
        } else {
            Cookie::queue(Cookie::forget('remember_email'));
            Cookie::queue(Cookie::forget('remember_password'));
        }

        $user->updated_at = now();
        $user->save();

        return redirect($user->getDashboardRoute());

    }

    // Tampilkan halaman register
    public function showRegister()
    {
        if (Session::has('user_id')) {
            return $this->redirectToDashboard(Session::get('user_role'));
        }
        
        return view('register');
    }

    // Proses register - DIPERBAIKI
    public function processRegister(Request $request)
    {
        // Validasi input dengan field name yang benar dari form
        $request->validate([
            'username' => 'required|string|min:3|max:100',
            'email' => 'required|email|ends_with:@gmail.com',
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
            'password_confirmation' => 'required|same:password'
        ], [
            'username.required' => 'Username tidak boleh kosong',
            'username.min' => 'Username minimal 3 karakter',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.ends_with' => 'Email harus menggunakan @gmail.com',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter',
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar dan 1 angka',
            'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong',
            'password_confirmation.same' => 'Password tidak cocok'
        ]);

        // Cek username dan email yang sudah ada
        $existingUser = User::where('username', $request->username) // <- gunakan username
            ->orWhere('email', $request->email)
            ->first();

        if ($existingUser) {
            if ($existingUser->email === $request->email) {
                return back()->withErrors(['email' => 'Email sudah terdaftar'])->withInput();
            }
            if ($existingUser->username === $request->username) {
                return back()->withErrors(['username' => 'Username sudah digunakan'])->withInput();
            }
        }

        try {
            // Create user baru
            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = $request->password; // Auto-hashed via model
            $user->role = 'pengguna';
            $user->status_akun = 'active';
            $user->save();

            // Kirim notifikasi selamat datang
            DB::table('notifikasi')->insert([
                'id_user' => $user->id_user,
                'pesan' => 'Selamat datang di Re-Glow! Mulai perjalanan Anda untuk memberikan dampak positif pada planet.',
                'waktu_kirim' => now(),
                'status' => 'unread'
            ]);

            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');

        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    // Logout
    public function logout(Request $request)
    {
        $sessionId = Session::get('session_id');

        if ($sessionId) {
            DB::table('session')->where('session_id', $sessionId)->delete();
        }

        Session::flush();

        return redirect()->route('welcome')->with('success', 'Anda berhasil logout');
    }

    // Helper function
    private function redirectToDashboard($role)
    {
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

    // Middleware untuk cek session
    public function checkSession(Request $request)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        $sessionId = Session::get('session_id');
        $dbSession = DB::table('session')
            ->where('session_id', $sessionId)
            ->first();

        if (!$dbSession || now()->greaterThan($dbSession->waktu_expire)) {
            Session::flush();
            return redirect()->route('login')->withErrors(['error' => 'Session Anda telah berakhir']);
        }

        return true;
    }
}