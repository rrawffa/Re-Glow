<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PointTransaction;
use App\Models\PointTraits;
use Illuminate\Support\Facades\Auth;

class RiwayatPoinController extends Controller
{
    // User: lihat riwayat poin
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $points = new PointTraits($user);

        return view('riwayat_poin.poinhistory', [
            'current_balance' => $points->currentBalance(),
            'total_earned' => $points->totalEarned(),
            'total_redeemed' => $points->totalRedeemed(),
            'transactions' => $points->transactions()->latest()->get(),
        ]);
    }

    // Admin: tambah poin
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:pengguna,id_user',
            'type' => 'required|in:earn,redeem,adjust',
            'points' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        PointTransaction::create($request->only(['user_id','type','points','description']));

        return redirect()->back()->with('success', 'Transaksi poin berhasil ditambahkan.');
    }

    // Admin: update poin
    public function update(Request $request, $id)
    {
        $transaction = PointTransaction::findOrFail($id);

        $request->validate([
            'points' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        $transaction->update($request->only(['points','description']));

        return redirect()->back()->with('success', 'Transaksi poin berhasil diupdate.');
    }

    // Admin: hapus poin
    public function destroy($id)
    {
        $transaction = PointTransaction::findOrFail($id);
        $transaction->delete();

        return redirect()->back()->with('success', 'Transaksi poin berhasil dihapus.');
    }
}
