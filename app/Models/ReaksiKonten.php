<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReaksiKonten extends Model
{
    use HasFactory;

    protected $table = 'reaksikonten';
    protected $primaryKey = 'id_reaksi';
    public $timestamps = true;

    protected $fillable = [
        'id_konten',
        'id_user',
        'tipe_reaksi',
        'ip_address'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    // Relasi ke education content
    public function education()
    {
        return $this->belongsTo(Education::class, 'id_konten', 'id_konten');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
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