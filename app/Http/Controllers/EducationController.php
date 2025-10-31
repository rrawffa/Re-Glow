<?php
// app/Http/Controllers/EducationController.php

namespace App\Http\Controllers;

use App\Models\KontenEdukasi;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index()
    {
        // Data dummy sederhana
        $konten = [
            (object)[
                'judul' => 'The Hidden Impact of Beauty Packaging',
                'ringkasan' => 'Learn about the environmental footprint of cosmetic packaging.',
                'tanggal_upload' => '2024-10-15'
            ],
            (object)[
                'judul' => 'DIY Natural Skincare Recipes',
                'ringkasan' => 'Create effective skincare products at home.',
                'tanggal_upload' => '2024-10-12'
            ],
            (object)[
                'judul' => 'Sustainable Skincare Guide',
                'ringkasan' => 'Complete guide to eco-friendly beauty routines.',
                'tanggal_upload' => '2024-10-10'
            ]
        ];

        return view('education.index', compact('konten'));
    }

    public function show($id)
    {
        $konten = KontenEdukasi::published()
            ->with(['media', 'kategori'])
            ->findOrFail($id);
        
        // Konten terkait (berdasarkan kategori yang sama)
        $related = KontenEdukasi::published()
            ->where('id_konten', '!=', $id)
            ->whereHas('kategori', function($query) use ($konten) {
                $query->whereIn('id_kategori', $konten->kategori->pluck('id_kategori'));
            })
            ->limit(3)
            ->get();
        
        return view('education.show', compact('konten', 'related'));
    }
}