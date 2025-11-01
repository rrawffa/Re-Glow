<?php
// app/Models/Education.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'kontenedukasi';
    protected $primaryKey = 'id_konten';
    public $timestamps = true;

    protected $fillable = [
        'judul',
        'ringkasan',
        'isi',
        'gambar_cover',
        'penulis',
        'tanggal_upload',
        'waktu_baca',
        'status'
    ];

    protected $casts = [
        'tanggal_upload' => 'date',
    ];

    // Relasi ke statistik
    public function statistik()
    {
        return $this->hasOne(StatistikEdukasi::class, 'id_konten', 'id_konten');
    }

    // Relasi ke reaksi
    public function reaksi()
    {
        return $this->hasMany(ReaksiKonten::class, 'id_konten', 'id_konten');
    }

    // Relasi ke media
    public function media()
    {
        return $this->hasMany(MediaKonten::class, 'id_konten', 'id_konten')->orderBy('urutan');
    }

    // Check if user has reacted
    public function hasUserReacted($userId = null, $ipAddress = null)
    {
        $query = $this->reaksi();
        
        if ($userId) {
            $query->where('id_user', $userId);
        } else {
            $query->where('ip_address', $ipAddress);
        }
        
        return $query->first();
    }

    // Get reaction counts
    public function getReactionCounts()
    {
        if ($this->statistik) {
            return [
                'suka' => $this->statistik->total_suka,
                'membantu' => $this->statistik->total_membantu,
                'menarik' => $this->statistik->total_menarik,
                'inspiratif' => $this->statistik->total_inspiratif,
                'total' => $this->statistik->total_suka + 
                          $this->statistik->total_membantu + 
                          $this->statistik->total_menarik + 
                          $this->statistik->total_inspiratif
            ];
        }
        
        return [
            'suka' => 0,
            'membantu' => 0,
            'menarik' => 0,
            'inspiratif' => 0,
            'total' => 0
        ];
    }

    // Increment view count
    public function incrementView()
    {
        if ($this->statistik) {
            // Gunakan update manual untuk menghindari updated_at
            $this->statistik->update([
                'total_view' => $this->statistik->total_view + 1,
                'last_updated' => now()
            ]);
        } else {
            StatistikEdukasi::create([
                'id_konten' => $this->id_konten,
                'total_view' => 1,
                'total_suka' => 0,
                'total_membantu' => 0,
                'total_menarik' => 0,
                'total_inspiratif' => 0,
                'last_updated' => now()
            ]);
        }
    }
}