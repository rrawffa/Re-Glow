<?php

namespace App\Http\Controllers;

use App\Models\JadwalPengambilan;
use App\Models\TransaksiSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LogistikController extends Controller
{
    /**
     * Show logistics schedule
     */
    public function schedule()
    {
        // Get today's pickups
        $todayPickups = JadwalPengambilan::whereDate('waktu_pengambilan', today())
            ->with(['transaksi', 'dropPoint', 'user'])
            ->orderBy('waktu_pengambilan', 'asc')
            ->get();

        // Get completed pickups today
        $completedToday = JadwalPengambilan::whereDate('waktu_pengambilan', today())
            ->where('status', 'Selesai')
            ->count();

        // Get pending pickups today
        $pendingToday = JadwalPengambilan::whereDate('waktu_pengambilan', today())
            ->whereIn('status', ['Pending', 'Dikonfirmasi'])
            ->count();

        // Get total pickups
        $totalPickups = JadwalPengambilan::count();

        return view('logistik.schedule', [
            'todayPickups' => $todayPickups,
            'completedToday' => $completedToday,
            'pendingToday' => $pendingToday,
            'totalPickups' => $totalPickups,
        ]);
    }

    /**
     * Show logistics dashboard
     */
    public function dashboard()
    {
        // Get today's pickups
        $todayPickups = JadwalPengambilan::whereDate('waktu_pengambilan', today())
            ->with(['transaksi', 'dropPoint', 'user'])
            ->orderBy('waktu_pengambilan', 'asc')
            ->get();

        // Get completed pickups today
        $completedToday = JadwalPengambilan::whereDate('waktu_pengambilan', today())
            ->where('status', 'Selesai')
            ->count();

        // Get pending pickups today
        $pendingToday = JadwalPengambilan::whereDate('waktu_pengambilan', today())
            ->whereIn('status', ['Pending', 'Dikonfirmasi'])
            ->count();

        // Get total pickups for this logistics team (all time)
        $totalPickups = JadwalPengambilan::count();

        // Get recent pickup history (last 10)
        $recentPickups = JadwalPengambilan::with(['transaksi', 'dropPoint', 'user'])
            ->orderBy('waktu_pengambilan', 'desc')
            ->take(10)
            ->get();

        // Calculate active vehicles (distinct drop points with pending/confirmed pickups today)
        $activeVehicles = JadwalPengambilan::whereDate('waktu_pengambilan', today())
            ->whereIn('status', ['Pending', 'Dikonfirmasi'])
            ->distinct()
            ->count('id_drop_point');

        return view('logistik.dashboard', [
            'todayPickups' => $todayPickups,
            'completedToday' => $completedToday,
            'pendingToday' => $pendingToday,
            'totalPickups' => $totalPickups,
            'recentPickups' => $recentPickups,
            'activeVehicles' => $activeVehicles,
        ]);
    }

    /**
     * Get pickup details
     */
    public function getPickupDetails($id)
    {
        $pickup = JadwalPengambilan::with(['transaksi', 'dropPoint', 'user'])
            ->findOrFail($id);

        return response()->json($pickup);
    }

    /**
     * Update pickup status
     */
    public function updatePickupStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Dikonfirmasi,Selesai,Bermasalah'
        ]);

        $pickup = JadwalPengambilan::findOrFail($id);
        $pickup->updateStatus($request->status);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'pickup' => $pickup
        ]);
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats()
    {
        return response()->json([
            'today_pickups' => JadwalPengambilan::whereDate('waktu_pengambilan', today())->count(),
            'completed_today' => JadwalPengambilan::whereDate('waktu_pengambilan', today())
                ->where('status', 'Selesai')->count(),
            'pending_today' => JadwalPengambilan::whereDate('waktu_pengambilan', today())
                ->whereIn('status', ['Pending', 'Dikonfirmasi'])->count(),
            'total_pickups' => JadwalPengambilan::count(),
            'active_vehicles' => JadwalPengambilan::whereDate('waktu_pengambilan', today())
                ->whereIn('status', ['Pending', 'Dikonfirmasi'])
                ->distinct()
                ->count('id_drop_point'),
        ]);
    }
}
