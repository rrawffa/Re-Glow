<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaKonten extends Model
{
    use HasFactory;

    protected $table = 'mediakonten'; // Sesuaikan dengan nama tabel sebenarnya
    protected $primaryKey = 'id_media';
    public $timestamps = true;

    protected $fillable = [
        'id_konten',
        'nama_file',
        'tipe_file', // Sesuai dengan kolom di tabel
        'urutan',
        'caption', // Sesuai dengan kolom di tabel
        'created_at'
    ];

    protected $casts = [
        'urutan' => 'integer',
        'created_at' => 'datetime'
    ];

    // Relasi ke education content
    public function education()
    {
        return $this->belongsTo(Education::class, 'id_konten', 'id_konten');
    }

    // Accessor untuk full URL file
    public function getUrlAttribute()
    {
        // Sesuaikan dengan path penyimpanan file Anda
        return asset('storage/media/' . $this->nama_file);
    }

    // Scope untuk mengurutkan
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }
}