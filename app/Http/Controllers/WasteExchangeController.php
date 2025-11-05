<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiSampah;
use App\Models\DetailSampah;
use App\Models\RiwayatSampah;
use App\Models\JadwalPengambilan;
use App\Models\DropPoint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; 

class WasteExchangeController extends Controller
{
    // Landing page
    public function index()
    {
        $dropPoints = DropPoint::all();
        
        // Statistik
        $stats = [
            'total_transaksi' => TransaksiSampah::count(),
            'total_poin' => TransaksiSampah::sum('total_poin'),
            'drop_locations' => DropPoint::count()
        ];

        return view('waste-exchange.index', compact('dropPoints', 'stats'));
    }

    // Form create
    public function create()
    {
        $dropPoints = DropPoint::all();
        
        $packagingCategories = [
            'Plastic Bottle' => 'Plastic Bottle',
            'Glass Jar' => 'Glass Jar', 
            'Metal Tube' => 'Metal Tube',
            'Compact Case' => 'Compact Case'
        ];

        $sizeCategories = [
            'Large' => '"Large" - >100ml/large palette',
            'Medium' => '"Medium" - 50-100ml/standard jar',
            'Small' => '"Small" - <50ml/lipstick/eyeshadow single'
        ];

        return view('waste-exchange.create', compact('dropPoints', 'packagingCategories', 'sizeCategories'));
    }

    // Store transaction
    public function store(Request $request)
    {
        Log::info('=== WASTE EXCHANGE STORE METHOD CALLED ===');
        Log::info('User ID: ' . Session::get('user_id'));
        Log::info('Request data:', $request->except(['foto_bukti'])); // Exclude file for readability
        
        $validator = Validator::make($request->all(), [
            'id_drop_point' => 'required|exists:drop_points,id_drop_point',
            'products' => 'required|array|min:1',
            'products.*.nama_produk' => 'required|string|max:100',
            'products.*.packaging_category' => 'required|in:Plastic Bottle,Glass Jar,Metal Tube,Compact Case',
            'products.*.size_category' => 'required|in:Large,Medium,Small',
            'products.*.quantity' => 'required|integer|min:1',
            'foto_bukti' => 'required|image|mimes:jpeg,png,jpg|max:10240'
        ], [
            'id_drop_point.required' => 'Drop point harus dipilih',
            'products.required' => 'Minimal harus ada 1 produk',
            'products.*.nama_produk.required' => 'Nama produk harus diisi',
            'products.*.packaging_category.required' => 'Kategori packaging harus dipilih',
            'products.*.size_category.required' => 'Ukuran harus dipilih',
            'products.*.quantity.required' => 'Jumlah harus diisi',
            'foto_bukti.required' => 'Foto bukti harus diupload'
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form.')
                ->with('error_fields', $validator->errors()->keys());
        }
        
        try {
            DB::beginTransaction();
            Log::info('Transaction started');

            // Upload foto bukti
            $fotoPath = null;
            if ($request->hasFile('foto_bukti')) {
                $file = $request->file('foto_bukti');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/waste_proof'), $filename);
                $fotoPath = 'uploads/waste_proof/' . $filename;
            }

            // Create transaction
            $transaksi = TransaksiSampah::create([
                'id_user' => Session::get('user_id'),
                'id_drop_point' => $request->id_drop_point,
                'tgl_tSampah' => now(),
                'foto_bukti' => $fotoPath,
                'status' => 'Menunggu'
            ]);

            // Create details & calculate points
            $totalPoin = 0;
            $jenisArray = [];
            
            foreach ($request->products as $product) {
                $poinMap = [
                    'Large' => 50,
                    'Medium' => 30,
                    'Small' => 15
                ];
                
                $poin = $poinMap[$product['size_category']] * $product['quantity'];
                
                DetailSampah::create([
                    'id_tSampah' => $transaksi->id_tSampah,
                    'jenis_sampah' => $product['nama_produk'],
                    'ukuran_sampah' => $product['size_category'],
                    'quantity' => $product['quantity'],
                    'poin_per_sampah' => $poin
                ]);
                
                $totalPoin += $poin;
                $jenisArray[] = $product['nama_produk'];
            }

            // Update total poin
            $transaksi->update(['total_poin' => $totalPoin]);

            // Create riwayat awal
            RiwayatSampah::create([
                'id_tSampah' => $transaksi->id_tSampah,
                'status' => 'Menunggu',
                'tanggal_update' => now()
            ]);

            // Create jadwal pengambilan untuk tim logistik
            $dropPoint = DropPoint::find($request->id_drop_point);
            
            JadwalPengambilan::create([
                'id_transaksi' => $transaksi->id_tSampah,
                'id_user' => Session::get('user_id'),
                'id_drop_point' => $request->id_drop_point,
                'lokasi_droppoint' => $dropPoint->nama_lokasi,
                'koordinat_lokasi' => $dropPoint->koordinat,
                'jenis_sampah' => implode(', ', array_unique($jenisArray)),
                'waktu_pengambilan' => now()->addDays(1),
                'status' => 'Pending',
                'catatan_pengguna' => 'Sampah sudah dipisahkan'
            ]);

            DB::commit();
            Log::info('Transaction committed successfully. Redirecting to history.');

            return redirect()
                ->route('waste-exchange.history')
                ->with('success', 'Transaksi berhasil dibuat! Estimasi poin: +' . $totalPoin . ' points');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // History
    public function history()
    {
        $userId = Session::get('user_id');
        
        $statusCounts = [
            'menunggu' => TransaksiSampah::where('id_user', $userId)->where('status', 'Menunggu')->count(),
            'diproses' => TransaksiSampah::where('id_user', $userId)->where('status', 'Diproses')->count(),
            'selesai' => TransaksiSampah::where('id_user', $userId)->where('status', 'Selesai')->count(),
        ];

        $transactions = TransaksiSampah::with(['dropPoint', 'details', 'jadwalPengambilan'])
            ->where('id_user', $userId)
            ->orderBy('tgl_tSampah', 'desc')
            ->paginate(10);

        return view('waste-exchange.history', compact('transactions', 'statusCounts'));
    }

    // Show detail
    public function show($id)
    {
        $transaksi = TransaksiSampah::with([
            'dropPoint', 
            'details', 
            'riwayat', 
            'jadwalPengambilan'
        ])
        ->where('id_tSampah', $id)
        ->where('id_user', Session::get('user_id'))
        ->firstOrFail();

        return view('waste-exchange.show', compact('transaksi'));
    }

    // Edit
    public function edit($id)
    {
        $transaksi = TransaksiSampah::with(['dropPoint', 'details'])
            ->where('id_tSampah', $id)
            ->where('id_user', Session::get('user_id'))
            ->firstOrFail();

        if ($transaksi->status !== 'Menunggu') {
            return redirect()
                ->route('waste-exchange.history')
                ->with('error', 'Transaksi tidak dapat diedit');
        }

        $dropPoints = DropPoint::all();
        
        $packagingCategories = [
            'Plastic Bottle' => 'Plastic Bottle',
            'Glass Jar' => 'Glass Jar',
            'Metal Tube' => 'Metal Tube',
            'Compact Case' => 'Compact Case'
        ];

        $sizeCategories = [
            'Large' => '"Large" - >100ml/large palette',
            'Medium' => '"Medium" - 50-100ml/standard jar',
            'Small' => '"Small" - <50ml/lipstick/eyeshadow single'
        ];

        return view('waste-exchange.edit', compact('transaksi', 'dropPoints', 'packagingCategories', 'sizeCategories'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $transaksi = TransaksiSampah::where('id_tSampah', $id)
            ->where('id_user', Session::get('user_id'))
            ->firstOrFail();

        if ($transaksi->status !== 'Menunggu') {
            return redirect()
                ->route('waste-exchange.history')
                ->with('error', 'Transaksi tidak dapat diedit');
        }

        $validator = Validator::make($request->all(), [
            'id_drop_point' => 'required|exists:drop_points,id_drop_point',
            'products' => 'required|array|min:1',
            'products.*.jenis_sampah' => 'required|string|max:100',
            'products.*.packaging_category' => 'required',
            'products.*.ukuran_sampah' => 'required|in:Large,Medium,Small',
            'products.*.quantity' => 'required|integer|min:1',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:10240'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Upload foto baru jika ada
            if ($request->hasFile('foto_bukti')) {
                if ($transaksi->foto_bukti && file_exists(public_path($transaksi->foto_bukti))) {
                    unlink(public_path($transaksi->foto_bukti));
                }

                $file = $request->file('foto_bukti');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/waste_proof'), $filename);
                $transaksi->foto_bukti = 'uploads/waste_proof/' . $filename;
            }

            $transaksi->id_drop_point = $request->id_drop_point;
            $transaksi->save();

            // Delete old details
            DetailSampah::where('id_tSampah', $id)->delete();

            // Create new details
            $totalPoin = 0;
            foreach ($request->products as $product) {
                $poinMap = [
                    'Large' => 50,
                    'Medium' => 30,
                    'Small' => 15
                ];
                
                $poin = $poinMap[$product['ukuran_sampah']] * $product['quantity'];
                
                DetailSampah::create([
                    'id_tSampah' => $transaksi->id_tSampah,
                    'jenis_sampah' => $product['jenis_sampah'],
                    'ukuran_sampah' => $product['ukuran_sampah'],
                    'quantity' => $product['quantity'],
                    'poin_per_sampah' => $poin
                ]);
                
                $totalPoin += $poin;
            }

            $transaksi->update(['total_poin' => $totalPoin]);

            DB::commit();

            return redirect()
                ->route('waste-exchange.history')
                ->with('success', 'Transaksi berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Delete
    public function destroy($id)
    {
        $transaksi = TransaksiSampah::where('id_tSampah', $id)
            ->where('id_user', Session::get('user_id'))
            ->firstOrFail();

        if ($transaksi->status !== 'Menunggu') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak dapat dihapus'
            ], 403);
        }

        try {
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
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}