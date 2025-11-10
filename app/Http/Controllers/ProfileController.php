<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        // This app uses a custom session-based auth (Session::put('user_id') in AuthController)
        // so use the session to determine login state and load the user.
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }

        $userId = Session::get('user_id');
        // Primary key in your users table appears to be `id_user` — load the user accordingly.
        $user = User::where('id_user', $userId)->first();

        // If user record is missing for some reason, clear session and redirect to login
        if (!$user) {
            Session::flush();
            return redirect()->route('login');
        }

        $activeTab = $request->query('tab', 'posts');

        // TODO: replace with real posts retrieval when available
        $posts = collect([]);

        return view('user.profile', [
            'user' => $user,
            'posts' => $posts,
            'activeTab' => $activeTab
        ]);
    }
}
