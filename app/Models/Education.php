<?php
// app/Models/KontenEdukasi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'KontenEdukasi';
    protected $primaryKey = 'id_konten';
    
    protected $fillable = [
        'judul',
        'ringkasan',
        'isi',
        'gambar_cover',
        'penulis',
        'tanggal_upload',
        'waktu_baca',
        'jumlah_reaksi',
        'jumlah_share',
        'status'
    ];

    protected $casts = [
        'tanggal_upload' => 'date',
    ];

    // Relationship dengan media
    public function media()
    {
        return $this->hasMany(MediaKonten::class, 'id_konten', 'id_konten');
    }

    // Relationship dengan kategori
    public function kategori()
    {
        return $this->belongsToMany(KategoriEdukasi::class, 'KontenKategori', 'id_konten', 'id_kategori');
    }

    // Scope untuk konten published
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}