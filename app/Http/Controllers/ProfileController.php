<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ProfileController extends Controller
{
    // Show profile
    public function show(Request $request)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = User::where('id_user', Session::get('user_id'))->first();
        
        if (!$user) {
            Session::flush();
            return redirect()->route('login')->with('error', 'User tidak ditemukan');
        }

        return view('user.profile', ['user' => $user]);
    }

    // Edit profile form
    public function edit(Request $request, $id = null)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = User::where('id_user', Session::get('user_id'))->first();
        
        if (!$user) {
            Session::flush();
            return redirect()->route('login')->with('error', 'User tidak ditemukan');
        }

        return view('user.edit', ['user' => $user]);
    }

    // Update profile - DIPERBAIKI
    public function update(Request $request, $id = null)
    {
        // Log untuk debugging
        Log::info('Profile update attempt', [
            'session_user_id' => Session::get('user_id'),
            'request_data' => $request->except(['avatar', 'current_password'])
        ]);

        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = User::where('id_user', Session::get('user_id'))->first();
        
        if (!$user) {
            Session::flush();
            return redirect()->route('login')->with('error', 'User tidak ditemukan');
        }

        // PERBAIKAN: Gunakan validation rules dari model
        $validated = $request->validate(
            User::updateProfileRules($user->id_user),
            User::updateProfileMessages()
        );

        try {
            // PERBAIKAN: Cek apakah email atau no_hp diubah
            $emailChanged = isset($validated['email']) && $validated['email'] !== $user->email;
            $phoneChanged = isset($validated['no_hp']) && $validated['no_hp'] !== $user->no_hp;

            // PERBAIKAN: Validasi current password jika email atau no_hp diubah
            if (($emailChanged || $phoneChanged) && empty($validated['current_password'])) {
                return back()
                    ->withInput()
                    ->with('error', 'Password saat ini diperlukan untuk mengubah email atau nomor telepon');
            }

            // PERBAIKAN: Cek current password jika diperlukan
            if (($emailChanged || $phoneChanged) && !empty($validated['current_password'])) {
                if (!$user->checkCurrentPassword($validated['current_password'])) {
                    return back()
                        ->withInput()
                        ->with('error', 'Password saat ini salah');
                }
            }

            // Update username jika diisi
            if (!empty($validated['username'])) {
                $user->username = $validated['username'];
            }

            // PERBAIKAN: Update email jika diisi
            if (!empty($validated['email'])) {
                $user->email = $validated['email'];
                // Update session juga
                Session::put('email', $validated['email']);
            }

            // PERBAIKAN: Update no_hp jika diisi
            if (isset($validated['no_hp'])) {
                $user->no_hp = $validated['no_hp'];
            }

            // Simpan perubahan
            $user->save();

            // Update session username jika berubah
            if (!empty($validated['username'])) {
                Session::put('username', $validated['username']);
            }

            Log::info('Profile updated successfully', [
                'user_id' => $user->id_user,
                'changes' => $user->getChanges()
            ]);

            return redirect()->route('user.profile.show')
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Profile update failed', [
                'user_id' => $user->id_user,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage());
        }
    }
}