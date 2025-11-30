<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DropPoint;
use App\Models\TransaksiSampah;
use App\Models\JadwalPengambilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminWasteExchangeController extends Controller
{
    /**
     * Display waste exchange overview
     */
    public function index()
    {
        // Statistics for all
        $dropPoints = DropPoint::withCount(['transaksi'])->get();
        
        // Calculate capacity for each drop point
        foreach ($dropPoints as $dp) {
            $totalWeight = TransaksiSampah::where('id_drop_point', $dp->id_drop_point)
                ->whereIn('status', ['Menunggu', 'Diproses'])
                ->sum('total_poin') * 0.1; // Estimate 0.1kg per point
            
            $dp->current_capacity = $totalWeight;
            $dp->is_full = $totalWeight >= $dp->kapasitas_sampah;
        }

        $stats = [
            'total_droppoint' => DropPoint::count(),
            'total_transaksi' => TransaksiSampah::count(),
            'transaksi_pending' => TransaksiSampah::where('status', 'Menunggu')->count(),
            'transaksi_diproses' => TransaksiSampah::where('status', 'Diproses')->count(),
        ];

        return view('admin.waste-exchange.index', compact('dropPoints', 'stats'));
    }

    /**
     * Drop Point Management
     */
    public function dropPointIndex()
    {
        $dropPoints = DropPoint::orderBy('created_at', 'desc')->get();
        return view('admin.waste-exchange.drop-point', compact('dropPoints'));
    }

    public function dropPointCreate()
    {
        return view('admin.waste-exchange.drop-point-create');
    }

    public function dropPointStore(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|max:255',
            'koordinat' => 'required|max:100',
            'kapasitas_sampah' => 'required|numeric|min:0',
            'alamat' => 'required'
        ], [
            'nama_lokasi.required' => 'Nama lokasi tidak boleh kosong',
            'koordinat.required' => 'Koordinat tidak boleh kosong',
            'kapasitas_sampah.required' => 'Kapasitas sampah tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong'
        ]);

        try {
            DropPoint::create($validated);

            return redirect()
                ->route('admin.waste.droppoint.index')
                ->with('success', 'Drop point berhasil ditambahkan');

        } catch (\Exception $e) {
            Log::error('Drop point store error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function dropPointShow($id)
    {
        $dropPoint = DropPoint::with(['transaksi.user', 'transaksi.details'])
            ->findOrFail($id);

        $transactions = $dropPoint->transaksi()
            ->with('user', 'details')
            ->orderBy('tgl_tSampah', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'dropPoint' => $dropPoint,
                'transactions' => $transactions
            ]
        ]);
    }

    public function dropPointEdit($id)
    {
        $dropPoint = DropPoint::findOrFail($id);
        return view('admin.waste-exchange.drop-point-edit', compact('dropPoint'));
    }

    public function dropPointUpdate(Request $request, $id)
    {
        $dropPoint = DropPoint::findOrFail($id);

        $validated = $request->validate([
            'nama_lokasi' => 'required|max:255',
            'koordinat' => 'required|max:100',
            'kapasitas_sampah' => 'required|numeric|min:0',
            'alamat' => 'required'
        ], [
            'nama_lokasi.required' => 'Nama lokasi tidak boleh kosong',
            'koordinat.required' => 'Koordinat tidak boleh kosong',
            'kapasitas_sampah.required' => 'Kapasitas sampah tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong'
        ]);

        try {
            $dropPoint->update($validated);

            return redirect()
                ->route('admin.waste.droppoint.index')
                ->with('success', 'Drop point berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Drop point update error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function dropPointDestroy($id)
    {
        try {
            $dropPoint = DropPoint::findOrFail($id);
            
            // Check if has active transactions
            $hasActiveTransactions = TransaksiSampah::where('id_drop_point', $id)
                ->whereIn('status', ['Menunggu', 'Diproses'])
                ->exists();

            if ($hasActiveTransactions) {
                return response()->json([
                    'success' => false,
                    'message' => 'Drop point tidak dapat dihapus karena masih memiliki transaksi aktif'
                ], 400);
            }

            $dropPoint->delete();

            return response()->json([
                'success' => true,
                'message' => 'Drop point berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Drop point delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Transaction Management
     */
    public function transaksiIndex()
    {
        $transaksi = TransaksiSampah::with(['user', 'dropPoint', 'details'])
            ->orderBy('tgl_tSampah', 'desc')
            ->get();

        return view('admin.waste-exchange.transaksi', compact('transaksi'));
    }

    public function transaksiShow($id)
    {
        $transaksi = TransaksiSampah::with([
            'user',
            'dropPoint',
            'details',
            'riwayat',
            'jadwalPengambilan'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ]);
    }

    public function transaksiUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai'
        ]);

        try {
            $transaksi = TransaksiSampah::findOrFail($id);
            $transaksi->update(['status' => $request->status]);

            // Add to riwayat
            DB::table('riwayatsampah')->insert([
                'id_tSampah' => $transaksi->id_tSampah,
                'status' => $request->status,
                'tanggal_update' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status transaksi berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            Log::error('Transaction status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function transaksiDestroy($id)
    {
        try {
            $transaksi = TransaksiSampah::findOrFail($id);

            // Delete foto
            if ($transaksi->foto_bukti && file_exists(public_path($transaksi->foto_bukti))) {
                unlink(public_path($transaksi->foto_bukti));
            }

            $transaksi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Transaction delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logistics Schedule Management
     */
    public function logistikIndex()
    {
        $jadwal = JadwalPengambilan::with(['user', 'dropPoint', 'transaksi'])
            ->orderBy('waktu_pengambilan', 'desc')
            ->get();

        return view('admin.waste-exchange.logistik', compact('jadwal'));
    }
}