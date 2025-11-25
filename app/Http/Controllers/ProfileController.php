<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    // Show profile
    public function show(Request $request)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = User::where('id_user', Session::get('user_id'))->first();
        if (!$user) {
            Session::flush();
            return redirect()->route('login')->with('error', 'User not found');
        }

        return view('user.profile', ['user' => $user]);
    }

    // Edit profile form
    public function edit(Request $request, $id = null)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = User::where('id_user', Session::get('user_id'))->first();
        if (!$user) {
            Session::flush();
            return redirect()->route('login')->with('error', 'User not found');
        }

        return view('profile.edit', ['user' => $user]);
    }

    // Update profile
    public function update(Request $request, $id = null)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = User::where('id_user', Session::get('user_id'))->first();
        if (!$user) {
            Session::flush();
            return redirect()->route('login')->with('error', 'User not found');
        }

        $data = $request->validate([
            'username' => 'nullable|string|max:120',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Update username
        if (isset($data['username']) && !empty($data['username'])) {
            $user->username = $data['username'];
        }

        // Update bio if column exists
        if (isset($data['bio']) && !empty($data['bio']) && $user->hasColumn('bio')) {
            $user->bio = $data['bio'];
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->isValid()) {
                $path = $file->store('avatars', 'public');
                if ($path && $user->hasColumn('foto_profil')) {
                    // Delete old avatar if exists
                    if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                        Storage::disk('public')->delete($user->foto_profil);
                    }
                    $user->foto_profil = $path;
                }
            }
        }

        $user->save();

        return redirect()->route('user.profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
