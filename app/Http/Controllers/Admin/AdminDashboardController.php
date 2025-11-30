<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TransaksiSampah;
use App\Models\Voucher;
use App\Models\Education;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard with real data from database
     */
    public function index()
    {
        // 1. Total Users
        $totalUsers = User::where('role', 'pengguna')->count();

        // 2. Total Transactions
        $totalTransactions = TransaksiSampah::count();

        // 3. Active Vouchers (sum stock of all active vouchers)
        $activeVouchers = Voucher::where(function ($query) {
            // Exclude expired vouchers
            $query->whereNull('expiration_date')
                ->orWhere('expiration_date', '>=', now());
        })
            ->where('stock', '>', 0)
            ->sum('stock');

        // 4. Educational Posts
        $educationalPosts = Education::where('status', 'published')->count();

        // Additional stats for charts
        $transactionTypes = $this->getTransactionTypesData();
        $recentActivities = $this->getRecentActivities();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTransactions',
            'activeVouchers',
            'educationalPosts',
            'transactionTypes',
            'recentActivities'
        ));
    }

    /**
     * Get transaction types distribution (Waste Recycling vs Voucher Redemption only)
     * Total dari kedua kategori = 100%
     */
    private function getTransactionTypesData()
    {
        $total = TransaksiSampah::count();

        if ($total == 0) {
            return [
                'wasteRecycling' => 0,
                'voucherRedemption' => 0,
            ];
        }

        // Waste Recycling - all transactions with waste items
        $wasteRecycling = TransaksiSampah::whereHas('details')->count();

        // Voucher Redemption - remaining transactions (total - waste recycling)
        $voucherRedemption = $total - $wasteRecycling;

        // Calculate percentages where total = 100%
        $wastePercent = round(($wasteRecycling / $total) * 100);
        $voucherPercent = 100 - $wastePercent; // Ensure total is exactly 100%

        return [
            'wasteRecycling' => $wastePercent,
            'voucherRedemption' => $voucherPercent,
        ];
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        $activities = [];

        // Recent user registrations
        $recentUsers = User::where('role', 'pengguna')
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->get();

        foreach ($recentUsers as $user) {
            $activities[] = [
                'type' => 'user',
                'icon' => '👤',
                'title' => 'New user registration',
                'description' => $user->username . ' joined the platform',
                'time' => $user->created_at->diffForHumans(),
            ];
        }

        // Recent waste transactions
        $recentTransactions = TransaksiSampah::with('user')
            ->orderBy('tgl_tSampah', 'desc')
            ->limit(1)
            ->get();

        foreach ($recentTransactions as $transaction) {
            $totalQuantity = $transaction->details()->sum('quantity') ?? 0;
            $activities[] = [
                'type' => 'waste',
                'icon' => '♻️',
                'title' => 'Waste transaction completed',
                'description' => $totalQuantity . ' waste items recycled',
                'time' => $transaction->tgl_tSampah->diffForHumans(),
            ];
        }

        // Vouchers summary
        $activities[] = [
            'type' => 'voucher',
            'icon' => '🎫',
            'title' => 'Voucher Activity',
            'description' => 'Total ' . Voucher::count() . ' vouchers available',
            'time' => 'Updated today',
        ];

        return array_slice($activities, 0, 3);
    }
}
