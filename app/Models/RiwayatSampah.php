<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatSampah extends Model
{
    protected $table = 'riwayatsampah';
    protected $primaryKey = 'id_riwayat';
    
    protected $fillable = [
        'id_tSampah',
        'status',
        'tanggal_update'
    ];

    protected $casts = [
        'tanggal_update' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function transaksi()
    {
        return $this->belongsTo(TransaksiSampah::class, 'id_tSampah', 'id_tSampah');
    }

    /**
     * Get status icon for timeline
     */
    public function getStatusIcon()
    {
        $icons = [
            'Menunggu' => '📦',
            'Diproses' => '🚚',
            'Selesai' => '✅',
            'Bermasalah' => '⚠️',
            'Dibatalkan' => '❌'
        ];

        return $icons[$this->status] ?? '📌';
    }

    /**
     * Get status description
     */
    public function getStatusDescription()
    {
        $descriptions = [
            'Menunggu' => 'Transaction submitted successfully',
            'Diproses' => 'Items received at collection point',
            'Selesai' => 'Items being sorted and processed',
            'Bermasalah' => 'Issue detected, please contact support',
            'Dibatalkan' => 'Transaction cancelled by user'
        ];

        return $descriptions[$this->status] ?? 'Status updated';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        $classes = [
            'Menunggu' => 'bg-pink-100 text-pink-600',
            'Diproses' => 'bg-yellow-100 text-yellow-600',
            'Selesai' => 'bg-green-100 text-green-600',
            'Bermasalah' => 'bg-red-100 text-red-600',
            'Dibatalkan' => 'bg-gray-100 text-gray-600'
        ];

        return $classes[$this->status] ?? 'bg-gray-100 text-gray-600';
    }

    /**
     * Check if this is the current status
     */
    public function isCurrentStatus()
    {
        return $this->transaksi && $this->transaksi->status === $this->status;
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by transaction
     */
    public function scopeByTransaction($query, $transactionId)
    {
        return $query->where('id_tSampah', $transactionId);
    }

    /**
     * Scope: Recent updates
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('tanggal_update', 'desc')->limit($limit);
    }

    /**
     * Scope: Today's updates
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_update', today());
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->tanggal_update->format('M d, Y \a\t g:i A');
    }

    /**
     * Get relative time (e.g., "2 hours ago")
     */
    public function getRelativeTimeAttribute()
    {
        return $this->tanggal_update->diffForHumans();
    }
}