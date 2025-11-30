<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\TransaksiSampah;
use App\Models\Education;

class UserDashboardController extends Controller
{
    /**
     * Display user dashboard with real-time data
     */
    public function index()
    {
        $userId = Session::get('user_id');

        // 1. Waste Collected - Count of completed transactions for this user
        $wasteCollected = TransaksiSampah::where('id_user', $userId)
            ->where('status', 'Selesai')
            ->count();

        // 2. Points Earned - Sum of total_poin from all completed transactions
        $pointsEarned = TransaksiSampah::where('id_user', $userId)
            ->where('status', 'Selesai')
            ->sum('total_poin');

        // 3. Get recommended education posts
        $topArticles = Education::where('status', 'published')
            ->with('statistik')
            ->orderBy('tanggal_upload', 'desc')
            ->limit(3)
            ->get();

        return view('user.dashboard', compact(
            'wasteCollected',
            'pointsEarned',
            'topArticles'
        ));
    }

    /**
     * Get real-time stats via API for semi-real-time updates
     */
    public function getStats()
    {
        $userId = Session::get('user_id');

        $wasteCollected = TransaksiSampah::where('id_user', $userId)
            ->where('status', 'Selesai')
            ->count();

        $pointsEarned = TransaksiSampah::where('id_user', $userId)
            ->where('status', 'Selesai')
            ->sum('total_poin') ?? 0;

        return response()->json([
            'wasteCollected' => $wasteCollected,
            'pointsEarned' => $pointsEarned,
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
