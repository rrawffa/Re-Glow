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
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = auth()->user();
        $activeTab = $request->query('tab', 'posts');
        
        // Get user's posts based on active tab
        $posts = collect([]); // Initialize empty collection as default
        
        // You'll need to implement the actual post retrieval logic based on your database structure
        
        return view('user.profile', [
            'user' => $user,
            'posts' => $posts,
            'activeTab' => $activeTab
        ]);
    }
}
