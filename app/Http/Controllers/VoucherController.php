<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VoucherController extends Controller
{
    // ✅ Bisa diakses tanpa login
    public function index()
{
    $vouchers = Voucher::all();
    $userPoints = auth()->user()->points ?? 0;

    return view('vouchers.index', compact('vouchers', 'userPoints'));
}



    public function apiIndex(Request $request)
    {
        $page = max(1, (int) $request->get('page', 1));
        $perPage = 9;
        $skip = ($page - 1) * $perPage;
        $vouchers = Voucher::orderBy('created_at', 'desc')->skip($skip)->take($perPage)->get();

        return response()->json([
            'data' => $vouchers->map(function ($v) {
                return [
                    'id' => $v->id,
                    'name' => $v->name,
                    'brand' => $v->brand,
                    'required_points' => $v->required_points,
                    'expiration_date' => $v->expiration_date ? $v->expiration_date->format('d M Y') : null,
                    'stock' => $v->stock,
                    'image_url' => $v->image_url,
                    'description' => $v->description,
                ];
            }),
            'next_page' => count($vouchers) === $perPage ? $page + 1 : null,
        ]);
    }

    public function show(Voucher $voucher)
    {
        $user = Auth::check() ? Auth::user() : null;
        return view('vouchers.show', compact('voucher', 'user'));
    }

   public function redeem(Request $request, Voucher $voucher)
{
    $user = auth()->user();

    // Cek poin cukup
    if ($user->poin < $voucher->points_required) {
        return redirect()->back()->with('error', 'Poin Anda tidak mencukupi!');
    }

    // Kurangi poin user
    $user->poin -= $voucher->points_required;
    $user->save();

    // Kurangi stok voucher
    if ($voucher->stock > 0) {
        $voucher->stock -= 1;
        $voucher->save();
    }

    // Simpan riwayat redeem voucher
    DB::table('voucher_user')->insert([
        'user_id' => $user->id_user,
        'voucher_id' => $voucher->id,
        'redeemed_at' => now(),
    ]);

    // Simpan ke point_transactions untuk sejarah poin
    \App\Models\PointTransaction::create([
        'user_id' => $user->id_user,
        'points' => -$voucher->points_required,
        'type' => 'redeem',
        'description' => 'Redeem voucher: '.$voucher->title,
    ]);

    return redirect()->back()->with('success', 'Voucher berhasil diredeem!');
}

    public function favorites()
{
    // TODO: nanti bisa ambil dari tabel "favorites" user
    // Contoh: $favorites = Auth::user()->favoriteVouchers;
    $favorites = []; // sementara kosong

    return view('vouchers.favorites', compact('favorites'));
}

}
