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
        return view('admin.riwayat_poin.create');
    }

    // Simpan transaksi baru
  public function store(Request $request)
{
     $userId = session('user_id'); // Jika admin login sebagai user biasa

    // Jika admin pakai guard khusus, pakai ini:
    // $userId = auth('admin')->id();

    if (!$userId) {
        return back()->withErrors(['error' => 'No logged-in user found!']);
    }

    $request->validate([
        'type' => 'required|in:earn,redeem,adjust',
        'points' => 'required|integer',
        'description' => 'nullable|string',
    ]);

    PointTransaction::create([
        'user_id' => $userId,
        'type' => $request->type,
        'points' => $request->points,
        'description' => $request->description,
    ]);

    return redirect()->route('admin.riwayat_poin.index')
                     ->with('success', 'Point transaction added successfully!');
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

    // Validasi semua field
    $request->validate([

        'type' => 'required|in:earn,redeem,adjust',
        'points' => 'required|integer',
        'description' => 'nullable|string',
    ]);

    // Update semua field sekaligus
    $transaction->update($request->only(['type','points','description']));

    return redirect()->route('admin.riwayat_poin.index')
                     ->with('success', 'Point transaction updated successfully!');
}


    // Hapus transaksi (optional)
    public function destroy($id)
    {
        $transaction = PointTransaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('admin.riwayat_poin.index')->with('success', 'Point transaction deleted!');
    }
}
