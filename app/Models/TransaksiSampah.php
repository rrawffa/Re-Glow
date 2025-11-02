<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany(RiwayatSampah::class, 'id_tSampah', 'id_tSampah')->orderBy('tanggal_update', 'asc');
    }

    public function jadwalPengambilan()
    {
        return $this->hasOne(JadwalPengambilan::class, 'id_transaksi', 'id_tSampah');
    }

    /**
     * Helper Methods
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function canEdit()
    {
        return $this->status === 'Menunggu';
    }

    public function canDelete()
    {
        return $this->status === 'Menunggu';
    }

    public function isCompleted()
    {
        return $this->status === 'Selesai';
    }

    public function isProcessing()
    {
        return $this->status === 'Diproses';
    }

    public function isPending()
    {
        return $this->status === 'Menunggu';
    }

    /**
     * Update status dan create riwayat
     */
    public function updateStatus($newStatus)
    {
        $this->status = $newStatus;
        $this->save();

        // Create riwayat
        RiwayatSampah::create([
            'id_tSampah' => $this->id_tSampah,
            'status' => $newStatus,
            'tanggal_update' => now()
        ]);

        return $this;
    }

    /**
     * Get status badge class for UI
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

    /**
     * Get total items count
     */
    public function getTotalItemsAttribute()
    {
        return $this->details->sum('quantity');
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    /**
     * Scope: Recent transactions
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('tgl_tSampah', 'desc')->limit($limit);
    }

    /**
     * Scope: Completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Selesai');
    }

    /**
     * Delete override - also delete photo
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($transaksi) {
            // Delete photo file
            if ($transaksi->foto_bukti && file_exists(public_path($transaksi->foto_bukti))) {
                unlink(public_path($transaksi->foto_bukti));
            }

            // Details dan riwayat akan terhapus otomatis via cascade
        });
    }
}