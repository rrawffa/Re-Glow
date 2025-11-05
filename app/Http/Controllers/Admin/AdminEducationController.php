<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\StatistikEdukasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminEducationController extends Controller
{
    /**
     * Display admin education management dashboard
     */
    public function index()
    {
        $konten = Education::with('statistik')
            ->orderBy('tanggal_upload', 'desc')
            ->get();

        return view('admin.education.index', compact('konten'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.education.create');
    }

    /**
     * Store new education content
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|max:255',
            'ringkasan' => 'required|max:1000',
            'isi' => 'required',
            'gambar_cover' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'penulis' => 'required|max:100',
            'waktu_baca' => 'required|integer|min:1'
        ], [
            'judul.required' => 'Judul tidak boleh kosong',
            'ringkasan.required' => 'Ringkasan tidak boleh kosong',
            'isi.required' => 'Isi konten tidak boleh kosong',
            'gambar_cover.required' => 'Foto header harus diupload',
            'gambar_cover.image' => 'File harus berupa gambar',
            'gambar_cover.max' => 'Ukuran gambar maksimal 5MB',
            'penulis.required' => 'Nama penulis tidak boleh kosong',
            'waktu_baca.required' => 'Waktu baca harus diisi'
        ]);

        try {
            // Upload gambar cover
            if ($request->hasFile('gambar_cover')) {
                $file = $request->file('gambar_cover');
                $filename = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('education/covers', $filename, 'public');
                $validated['gambar_cover'] = $path;
            }

            $validated['tanggal_upload'] = now();
            $validated['konten'] = $validated['isi'];
            $validated['status'] = 'published';

            $konten = Education::create($validated);

            // Create statistics
            StatistikEdukasi::create([
                'id_konten' => $konten->id_konten,
                'total_view' => 0,
                'total_suka' => 0,
                'total_membantu' => 0,
                'total_menarik' => 0,
                'total_inspiratif' => 0,
                'last_updated' => now()
            ]);

            return redirect()
                ->route('admin.education.index')
                ->with('success', 'Konten edukasi berhasil ditambahkan');

        } catch (\Exception $e) {
            Log::error('Education store error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Show detail (preview)
     */
    public function show($id)
    {
        $konten = Education::with(['statistik'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $konten
        ]);
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $konten = Education::findOrFail($id);
        return view('admin.education.edit', compact('konten'));
    }

    /**
     * Update education content
     */
    public function update(Request $request, $id)
    {
        $konten = Education::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|max:255',
            'ringkasan' => 'required|max:1000',
            'isi' => 'required',
            'gambar_cover' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'penulis' => 'required|max:100',
            'waktu_baca' => 'required|integer|min:1'
        ], [
            'judul.required' => 'Judul tidak boleh kosong',
            'ringkasan.required' => 'Ringkasan tidak boleh kosong',
            'isi.required' => 'Isi konten tidak boleh kosong',
            'gambar_cover.image' => 'File harus berupa gambar',
            'gambar_cover.max' => 'Ukuran gambar maksimal 5MB',
            'penulis.required' => 'Nama penulis tidak boleh kosong',
            'waktu_baca.required' => 'Waktu baca harus diisi'
        ]);

        try {
            // Upload gambar baru jika ada
            if ($request->hasFile('gambar_cover')) {
                // Delete old image
                if ($konten->gambar_cover) {
                    Storage::disk('public')->delete($konten->gambar_cover);
                }
                
                $file = $request->file('gambar_cover');
                $filename = time() . '_' . Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('education/covers', $filename, 'public');
                $validated['gambar_cover'] = $path;
            }

            $validated['konten'] = $validated['isi'];
            $konten->update($validated);

            return redirect()
                ->route('admin.education.index')
                ->with('success', 'Konten edukasi berhasil diperbarui');

        } catch (\Exception $e) {
            Log::error('Education update error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete education content
     */
    public function destroy($id)
    {
        try {
            $konten = Education::findOrFail($id);

            // Delete image
            if ($konten->gambar_cover) {
                Storage::disk('public')->delete($konten->gambar_cover);
            }

            $konten->delete();

            return response()->json([
                'success' => true,
                'message' => 'Konten edukasi berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Education delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}