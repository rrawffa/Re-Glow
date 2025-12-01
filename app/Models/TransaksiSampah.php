<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RiwayatSampah;
use App\Models\PointTransaction;
use App\Models\DetailSampah;
use App\Models\User;
use App\Models\DropPoint;
use App\Models\JadwalPengambilan;

class TransaksiSampah extends Model
{
    protected $table = 'transaksisampah';
    protected $primaryKey = 'id_tSampah';

    protected $fillable = [
        'id_user',
        'id_drop_point',
        'tgl_tSampah',
        'foto_bukti',
        'status',
        'total_poin'
    ];

    protected $casts = [
        'tgl_tSampah' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function dropPoint()
    {
        return $this->belongsTo(DropPoint::class, 'id_drop_point', 'id_drop_point');
    }

    public function details()
    {
        return $this->hasMany(DetailSampah::class, 'id_tSampah', 'id_tSampah');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatSampah::class, 'id_tSampah', 'id_tSampah')
                    ->orderBy('tanggal_update', 'asc');
    }

    public function riwayatPoin()
    {
        return $this->hasOne(RiwayatPoin::class, 'id_tSampah', 'id_tSampah');
    }

    public function jadwalPengambilan()
    {
        return $this->hasOne(JadwalPengambilan::class, 'id_transaksi', 'id_tSampah');
    }

    /**
     * Update status & otomatis hitung + tambah poin saat Selesai
     */
public function updateStatus($newStatus)
{
    $this->status = $newStatus;
    $this->save();

    // Create riwayat status
    RiwayatSampah::create([
        'id_tSampah' => $this->id_tSampah,
        'status' => $newStatus,
        'tanggal_update' => now()
    ]);

    // Jika transaksi selesai → hitung poin & tambahkan ke user
    if ($newStatus === 'Selesai') {

        // Hitung total poin dari detail sampah
        $totalPoin = $this->details->sum(function ($item) {
            return $item->quantity * $item->poin_per_sampah;
        });

        // Update poin di transaksi
        $this->update(['total_poin' => $totalPoin]);

        // Masukkan ke riwayat poin
        PointTransaction::create([
            'user_id' => $this->id_user,
            'id_tSampah' => $this->id_tSampah,
            'points' => $totalPoin,
            'type' => 'IN',
            'description' => 'Poin dari transaksi sampah'
        ]);

        // Tambahkan poin ke user
        $this->user()->increment('poin', $totalPoin);
    }

    return $this;
}


    /**
     * Getter & Helper
     */
    public function getStatusBadgeClass()
    {
        $statusMap = [
            'Menunggu' => 'submitted',
            'Diproses' => 'processing',
            'Selesai' => 'completed'
        ];

        return $statusMap[$this->status] ?? 'default';
    }

    public function getTotalItemsAttribute()
    {
        return $this->details->sum('quantity');
    }

    /**
     * Scope
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('tgl_tSampah', 'desc')->limit($limit);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Selesai');
    }
}
