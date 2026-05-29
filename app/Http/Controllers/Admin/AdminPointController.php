<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminPointController extends Controller
{
    // Tampilkan list transaksi
    public function index()
    {
        $transactions = PointTransaction::with('user')->latest()->paginate(20);
        return view('admin.riwayat_poin.index', compact('transactions'));
    }

    // Form tambah transaksi
    public function create()
    {
        $users = User::all();
        return view('admin.riwayat_poin.create', compact('users'));
    }

    // Simpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:pengguna,id_user',
            'type' => 'required|in:earn,redeem,adjust',
            'points' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $transaction = PointTransaction::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'points' => $request->points,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.riwayat_poin.index')
                        ->with('success', 'Point transaction added successfully for user! Points are automatically synced.');
    }


    // Form edit transaksi
    public function edit($id)
    {
        $transaction = PointTransaction::findOrFail($id);
        $users = User::all();
        return view('admin.riwayat_poin.edit', compact('transaction', 'users'));
    }

    // Update transaksi
    public function update(Request $request, $id)
    {
        $transaction = PointTransaction::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:pengguna,id_user',
            'type' => 'required|in:earn,redeem,adjust',
            'points' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $transaction->update($request->only(['user_id', 'type', 'points', 'description']));

        return redirect()->route('admin.riwayat_poin.index')
                        ->with('success', 'Point transaction updated successfully! Points are automatically synced.');
    }


    // Hapus transaksi
    public function destroy($id)
    {
        $transaction = PointTransaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('admin.riwayat_poin.index')
                        ->with('success', 'Point transaction deleted! User points are automatically updated.');
    }
}
