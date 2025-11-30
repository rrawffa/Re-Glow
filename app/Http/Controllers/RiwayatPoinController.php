<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PointTransaction;
use App\Models\PointTraits;
use Illuminate\Support\Facades\Session;

class RiwayatPoinController extends Controller
{
    public function index()
    {
        // Ambil user_id dari session (bukan Auth)
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        // Ambil user dari database
        $user = User::where('id_user', $userId)->first();

        if (!$user) {
            return redirect()->route('login');
        }

        // Load point traits
         $points = new PointTraits($user);

        return view('riwayat_poin.poinhistory', [
            'current_balance' => $points->currentBalance(),
            'total_earned' => $points->totalEarned(),
            'total_redeemed' => $points->totalRedeemed(),
            'transactions' => $points->transactions()->latest()->get(),
        ]);
    }
}
