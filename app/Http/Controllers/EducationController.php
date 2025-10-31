<?php
namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index()
    {
        // ✅ Ambil data dari database yang statusnya 'published'
        $konten = Education::where('status', 'published')
            ->orderBy('tanggal_upload', 'desc')
            ->get();
        
        // ✅ Jika tidak ada data, gunakan data dummy
        if ($konten->isEmpty()) {
            $konten = collect([
                (object)[
                    'id_konten' => 1,
                    'judul' => 'The Hidden Impact of Beauty Packaging',
                    'ringkasan' => 'Learn about the environmental footprint of cosmetic packaging.',
                    'tanggal_upload' => '2024-10-15',
                    'gambar_cover' => null,
                    'waktu_baca' => 5,
                ],
                (object)[
                    'id_konten' => 2,
                    'judul' => 'DIY Natural Skincare Recipes',
                    'ringkasan' => 'Create effective skincare products at home.',
                    'tanggal_upload' => '2024-10-12',
                    'gambar_cover' => null,
                    'waktu_baca' => 8,
                ],
                (object)[
                    'id_konten' => 3,
                    'judul' => 'Sustainable Skincare Guide',
                    'ringkasan' => 'Complete guide to eco-friendly beauty routines.',
                    'tanggal_upload' => '2024-10-10',
                    'gambar_cover' => null,
                    'waktu_baca' => 10,
                ]
            ]);
        }

        return view('education.index', compact('konten'));
    }

    /**
     * Tampilkan detail artikel
     */
    public function show($id)
    {
        // ✅ Ambil konten berdasarkan id
        $konten = Education::where('status', 'published')
            ->where('id_konten', $id)
            ->firstOrFail();
        
        // ✅ Ambil konten terkait (3 artikel terbaru selain yang sedang dibuka)
        $related = Education::where('status', 'published')
            ->where('id_konten', '!=', $id)
            ->orderBy('tanggal_upload', 'desc')
            ->limit(3)
            ->get();
        
        return view('education.show', compact('konten', 'related'));
    }
}