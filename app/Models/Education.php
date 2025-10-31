<?php
// app/Models/KontenEdukasi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
    protected $table = 'kontenedukasi'; // atau 'KontenEdukasi' sesuai nama di database Anda
    protected $primaryKey = 'id_konten';
    public $incrementing = true;
    public $timestamps = true;
    
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

    // ✅ Cast tipe data
    protected $casts = [
        'tanggal_upload' => 'datetime',
        'waktu_baca' => 'integer',
        'jumlah_reaksi' => 'integer',
        'jumlah_share' => 'integer',
    ];

    // Scope untuk konten published
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    
    // Scope untuk konten draft
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}