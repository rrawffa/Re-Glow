<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPengambilan extends Model
{
    protected $table = 'jadwal_pengambilan';
    protected $primaryKey = 'id_jadwal_pengambilan';
    
    protected $fillable = [
        'id_transaksi',
        'id_user',
        'id_drop_point',
        'lokasi_droppoint',
        'koordinat_lokasi',
        'jenis_sampah',
        'waktu_pengambilan',
        'status',
        'catatan_pengguna'
    ];

    protected $casts = [
        'waktu_pengambilan' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function transaksi()
    {
        return $this->belongsTo(TransaksiSampah::class, 'id_transaksi', 'id_tSampah');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function dropPoint()
    {
        return $this->belongsTo(DropPoint::class, 'id_drop_point', 'id_drop_point');
    }

    public function riwayatPengambilan()
    {
        return $this->hasMany(RiwayatPengambilan::class, 'id_drop_point', 'id_drop_point');
    }

    /**
     * Status checking methods
     */
    public function isPending()
    {
        return $this->status === 'Pending';
    }

    public function isDikonfirmasi()
    {
        return $this->status === 'Dikonfirmasi';
    }

    public function isSelesai()
    {
        return $this->status === 'Selesai';
    }

    public function isBermasalah()
    {
        return $this->status === 'Bermasalah';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        $classes = [
            'Pending' => 'bg-yellow-100 text-yellow-600',
            'Dikonfirmasi' => 'bg-blue-100 text-blue-600',
            'Selesai' => 'bg-green-100 text-green-600',
            'Bermasalah' => 'bg-red-100 text-red-600'
        ];

        return $classes[$this->status] ?? 'bg-gray-100 text-gray-600';
    }

    /**
     * Get status icon
     */
    public function getStatusIcon()
    {
        $icons = [
            'Pending' => '⏳',
            'Dikonfirmasi' => '✔️',
            'Selesai' => '✅',
            'Bermasalah' => '⚠️'
        ];

        return $icons[$this->status] ?? '📌';
    }

    /**
     * Update status
     */
    public function updateStatus($newStatus)
    {
        $this->status = $newStatus;
        $this->save();

        // Update related transaction status
        if ($this->transaksi) {
            $statusMap = [
                'Pending' => 'Menunggu',
                'Dikonfirmasi' => 'Diproses',
                'Selesai' => 'Selesai',
                'Bermasalah' => 'Bermasalah'
            ];

            if (isset($statusMap[$newStatus])) {
                $this->transaksi->updateStatus($statusMap[$newStatus]);
            }
        }

        return $this;
    }

    /**
     * Check if scheduled for today
     */
    public function isScheduledForToday()
    {
        return $this->waktu_pengambilan->isToday();
    }

    /**
     * Check if overdue
     */
    public function isOverdue()
    {
        return $this->waktu_pengambilan->isPast() && !$this->isSelesai();
    }

    /**
     * Get latitude from koordinat
     */
    public function getLatitude()
    {
        if (!$this->koordinat_lokasi) return null;
        
        $coords = explode(',', $this->koordinat_lokasi);
        return isset($coords[0]) ? trim($coords[0]) : null;
    }

    /**
     * Get longitude from koordinat
     */
    public function getLongitude()
    {
        if (!$this->koordinat_lokasi) return null;
        
        $coords = explode(',', $this->koordinat_lokasi);
        return isset($coords[1]) ? trim($coords[1]) : null;
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeDikonfirmasi($query)
    {
        return $query->where('status', 'Dikonfirmasi');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'Selesai');
    }

    public function scopeBermasalah($query)
    {
        return $query->where('status', 'Bermasalah');
    }

    public function scopeScheduledForDate($query, $date)
    {
        return $query->whereDate('waktu_pengambilan', $date);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('waktu_pengambilan', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('waktu_pengambilan', '>=', now())
                     ->orderBy('waktu_pengambilan', 'asc');
    }

    public function scopeOverdue($query)
    {
        return $query->where('waktu_pengambilan', '<', now())
                     ->whereNotIn('status', ['Selesai']);
    }

    /**
     * Accessor: Formatted pickup time
     */
    public function getFormattedPickupTimeAttribute()
    {
        return $this->waktu_pengambilan->format('M d, Y \a\t g:i A');
    }

    /**
     * Accessor: Relative pickup time
     */
    public function getRelativePickupTimeAttribute()
    {
        return $this->waktu_pengambilan->diffForHumans();
    }
}