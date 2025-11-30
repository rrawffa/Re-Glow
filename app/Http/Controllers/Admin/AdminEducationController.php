<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\StatistikEdukasi;
use App\Models\ReaksiKonten;
use App\Models\MediaKonten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
     * Show detail (preview) - FIXED untuk menampilkan gambar
     */
    public function show($id)
    {
        $konten = Education::with(['statistik'])->findOrFail($id);
        
        // Format gambar cover URL
        $gambarUrl = $konten->gambar_cover 
            ? asset('storage/' . $konten->gambar_cover) 
            : null;
        
        return response()->json([
            'success' => true,
            'data' => [
                'id_konten' => $konten->id_konten,
                'judul' => $konten->judul,
                'ringkasan' => $konten->ringkasan,
                'isi' => $konten->isi,
                'konten' => $konten->konten,
                'gambar_cover' => $gambarUrl,
                'penulis' => $konten->penulis,
                'tanggal_upload' => $konten->tanggal_upload->format('d M Y'),
                'waktu_baca' => $konten->waktu_baca,
                'statistik' => $konten->statistik
            ]
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
     * Delete education content - FIXED
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $konten = Education::findOrFail($id);
            
            // Delete related data
            $this->deleteRelatedData($konten->id_konten);

            // Delete image
            if ($konten->gambar_cover) {
                Storage::disk('public')->delete($konten->gambar_cover);
            }

            // Delete the content
            $konten->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Konten edukasi berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Education delete error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete related data helper
     */
    private function deleteRelatedData($id_konten)
    {
        // Delete statistics
        StatistikEdukasi::where('id_konten', $id_konten)->delete();
        
        // Delete reactions - check if class exists
        if (class_exists(ReaksiKonten::class)) {
            ReaksiKonten::where('id_konten', $id_konten)->delete();
        }
        
        // Delete media records - check if class exists
        if (class_exists(MediaKonten::class)) {
            $this->deleteMediaFiles($id_konten);
            MediaKonten::where('id_konten', $id_konten)->delete();
        }
    }

    /**
     * Delete media files from storage
     */
    private function deleteMediaFiles($id_konten)
    {
        if (!class_exists(MediaKonten::class)) {
            return;
        }
        
        $mediaFiles = MediaKonten::where('id_konten', $id_konten)->get();
        
        foreach ($mediaFiles as $media) {
            if ($media->path_file) {
                Storage::disk('public')->delete($media->path_file);
            }
        }
    }
}