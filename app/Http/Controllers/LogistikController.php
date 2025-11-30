<?php

namespace App\Http\Controllers;

use App\Models\JadwalPengambilan;
use App\Models\TransaksiSampah;
use App\Models\DropPoint;
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

        // Get all drop points from admin management with transaction count
        $dropPoints = DropPoint::withCount('transaksi')
            ->orderBy('nama_lokasi', 'asc')
            ->get();

        // Get upcoming pickups (next 7 days)
        $upcomingPickups = JadwalPengambilan::whereBetween('waktu_pengambilan', [today(), today()->addDays(7)])
            ->with(['transaksi', 'dropPoint', 'user'])
            ->orderBy('waktu_pengambilan', 'asc')
            ->get();

        // Get pickup count per drop point for today
        $pickupsByDropPoint = JadwalPengambilan::select('id_drop_point')
            ->whereDate('waktu_pengambilan', today())
            ->groupBy('id_drop_point')
            ->pluck('id_drop_point');

        return view('logistik.schedule', [
            'todayPickups' => $todayPickups,
            'completedToday' => $completedToday,
            'pendingToday' => $pendingToday,
            'totalPickups' => $totalPickups,
            'dropPoints' => $dropPoints,
            'upcomingPickups' => $upcomingPickups,
            'pickupsByDropPoint' => $pickupsByDropPoint,
        ]);
    }

    /**
     * Show pickup history
     */
    public function history(Request $request)
    {
        $query = JadwalPengambilan::with(['transaksi', 'dropPoint', 'user']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range if provided
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('waktu_pengambilan', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('waktu_pengambilan', '<=', $request->to_date);
        }

        // Default: show last 30 days if no filter
        if (!$request->has('from_date') && !$request->has('to_date')) {
            $query->whereDate('waktu_pengambilan', '>=', now()->subDays(30));
        }

        $pickupHistory = $query->orderBy('waktu_pengambilan', 'desc')->paginate(15);

        // Get statistics
        $totalCompleted = JadwalPengambilan::where('status', 'Selesai')->count();
        $totalPickups = JadwalPengambilan::count();
        $completionRate = $totalPickups > 0 ? round(($totalCompleted / $totalPickups) * 100, 2) : 0;

        return view('logistik.history', [
            'pickupHistory' => $pickupHistory,
            'totalCompleted' => $totalCompleted,
            'totalPickups' => $totalPickups,
            'completionRate' => $completionRate,
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
