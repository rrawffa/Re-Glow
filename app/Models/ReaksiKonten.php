<?php
// app/Models/ReaksiKonten.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReaksiKonten extends Model
{
    use HasFactory;

    protected $table = 'reaksikonten';
    protected $primaryKey = 'id_reaksi';
    public $timestamps = false;

    protected $fillable = [
        'id_konten',
        'id_user',
        'tipe_reaksi',
        'ip_address',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    // ============= RELATIONSHIPS =============
    // Relasi ke education content
    public function education()
    {
        return $this->belongsTo(Education::class, 'id_konten', 'id_konten');
    }

    // Relasi ke User (yang menggunakan tabel pengguna)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user', 'id_user');
    }

    // Scope untuk reaksi berdasarkan user
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    // Scope untuk reaksi berdasarkan IP
    public function scopeByIp($query, $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }

    // Scope untuk reaksi tertentu
    public function scopeByType($query, $tipeReaksi)
    {
        return $query->where('tipe_reaksi', $tipeReaksi);
    }
}