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
        // Jika sudah login, redirect ke dashboard sesuai role
        if (Session::has('user_id')) {
            return $this->redirectToDashboard(Session::get('user_role'));
        }
        
        return view('login');
    }

    // Proses login
    public function processLogin(Request $request)
    {
        // Validasi input menggunakan User model
        $request->validate([
            'email' => 'required|email|ends_with:@gmail.com',
            'password' => 'required'
        ], [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.ends_with' => 'Email harus menggunakan @gmail.com',
            'password.required' => 'Password tidak boleh kosong'
        ]);

        // Cek kredensial menggunakan model User
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar'])->withInput();
        }

        // Verifikasi password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah'])->withInput();
        }

        // Cek status akun
        if (!$user->isActive()) {
            return back()->withErrors(['email' => 'Akun Anda tidak aktif'])->withInput();
        }

        // Buat session
        $sessionId = Str::random(64);
        $expireTime = now()->addHours(24);

        // Simpan session ke database
        DB::table('session')->insert([
            'session_id' => $sessionId,
            'id_user' => $user->id_user,
            'waktu_login' => now(),
            'waktu_expire' => $expireTime,
            'ip_address' => $request->ip()
        ]);

        // Set session Laravel
        Session::put('user_id', $user->id_user);
        Session::put('username', $user->username);
        Session::put('email', $user->email);
        Session::put('user_role', $user->role);
        Session::put('session_id', $sessionId);

        // Handle "Remember Me"
        if ($request->has('remember')) {
            Cookie::queue('remember_email', $request->email, 43200); // 30 hari
            Cookie::queue('remember_password', $request->password, 43200);
        } else {
            Cookie::queue(Cookie::forget('remember_email'));
            Cookie::queue(Cookie::forget('remember_password'));
        }

        // Update last login
        $user->updated_at = now();
        $user->save();

        // Redirect ke dashboard sesuai role
        return redirect($user->getDashboardRoute());
    }

    // Tampilkan halaman register
    public function showRegister()
    {
        // Jika sudah login, redirect ke dashboard
        if (Session::has('user_id')) {
            return $this->redirectToDashboard(Session::get('user_role'));
        }
        
        return view('register');
    }

    // Proses register
    public function processRegister(Request $request)
    {
        // Validasi input menggunakan User model
        $request->validate(
            User::validationRules(),
            User::validationMessages()
        );

        // Cek apakah username atau email sudah ada
        $existingUser = User::where('username', $request->name)
            ->orWhere('email', $request->email)
            ->first();

        if ($existingUser) {
            if ($existingUser->email === $request->email) {
                return back()->withErrors(['email' => 'Email sudah terdaftar'])->withInput();
            }
            if ($existingUser->username === $request->name) {
                return back()->withErrors(['name' => 'Username sudah digunakan'])->withInput();
            }
        }

        try {
            // Create user baru menggunakan model
            $user = new User();
            $user->username = $request->name;
            $user->email = $request->email;
            $user->password = $request->password; // Auto-hashed via model boot method
            $user->role = 'pengguna'; // Default role
            $user->status_akun = 'active';
            $user->save();

            // Kirim notifikasi selamat datang
            DB::table('notifikasi')->insert([
                'id_user' => $user->id_user,
                'pesan' => 'Selamat datang di Re-Glow! Mulai perjalanan Anda untuk memberikan dampak positif pada planet.',
                'waktu_kirim' => now(),
                'status' => 'unread'
            ]);

            // Redirect ke login dengan pesan sukses
            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.'])->withInput();
        }
    }

    // Logout
    public function logout(Request $request)
    {
        $sessionId = Session::get('session_id');

        // Hapus session dari database
        if ($sessionId) {
            DB::table('session')->where('session_id', $sessionId)->delete();
        }

        // Clear session Laravel
        Session::flush();

        return redirect()->route('welcome')->with('success', 'Anda berhasil logout');
    }

    // Helper function untuk redirect ke dashboard
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

        // Cek apakah session masih valid
        if (!$dbSession || now()->greaterThan($dbSession->waktu_expire)) {
            Session::flush();
            return redirect()->route('login')->withErrors(['error' => 'Session Anda telah berakhir']);
        }

        return true;
    }
}