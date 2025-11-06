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
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login untuk menukarkan voucher.');
        }

        if ($voucher->expiration_date && Carbon::parse($voucher->expiration_date)->isPast()) {
            return back()->with('error', 'Voucher sudah kadaluarsa.');
        }

        if ($voucher->stock <= 0) {
            return back()->with('error', 'Voucher habis.');
        }

        if ($user->points < $voucher->required_points) {
            return back()->with('error', 'Poin tidak mencukupi.');
        }

        DB::transaction(function () use ($user, $voucher) {
            $voucher->decrement('stock');
            $user->decrement('points', $voucher->required_points);

            DB::table('voucher_redemptions')->insert([
                'user_id' => $user->id,
                'voucher_id' => $voucher->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return back()->with('success', 'Redemption successful. Voucher has been redeemed!');
    }

    public function favorites()
{
    // TODO: nanti bisa ambil dari tabel "favorites" user
    // Contoh: $favorites = Auth::user()->favoriteVouchers;
    $favorites = []; // sementara kosong

    return view('vouchers.favorites', compact('favorites'));
}

}
