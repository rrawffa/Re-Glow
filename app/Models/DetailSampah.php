<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSampah extends Model
{
    protected $table = 'detailsampah';
    protected $primaryKey = 'id_detail';
    
    protected $fillable = [
        'id_tSampah',
        'jenis_sampah',
        'ukuran_sampah',
        'quantity',
        'poin_per_sampah'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'poin_per_sampah' => 'integer',
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
     * Calculate points based on size and quantity
     */
    public function hitungPoin()
    {
        $poinMap = [
            'Large' => 50,
            'Medium' => 30,
            'Small' => 15
        ];
        
        $poinPerItem = $poinMap[$this->ukuran_sampah] ?? 0;
        return $poinPerItem * $this->quantity;
    }

    /**
     * Get jenis sampah
     */
    public function getJenis()
    {
        return $this->jenis_sampah;
    }

    /**
     * Get ukuran sampah
     */
    public function getUkuran()
    {
        return $this->ukuran_sampah;
    }

    /**
     * Get size label for display
     */
    public function getSizeLabelAttribute()
    {
        $sizeLabels = [
            'Large' => '>100ml/large palette',
            'Medium' => '50-100ml/standard jar',
            'Small' => '<50ml/lipstick/eyeshadow'
        ];

        return $sizeLabels[$this->ukuran_sampah] ?? $this->ukuran_sampah;
    }

    /**
     * Get packaging icon
     */
    public function getPackagingIcon()
    {
        $icons = [
            'Plastic Bottle' => '🧴',
            'Glass Jar' => '🫙',
            'Metal Tube' => '🧪',
            'Compact Case' => '💄'
        ];

        return $icons[$this->jenis_sampah] ?? '📦';
    }

    /**
     * Scope: Filter by transaction
     */
    public function scopeByTransaction($query, $transactionId)
    {
        return $query->where('id_tSampah', $transactionId);
    }

    /**
     * Scope: Filter by size
     */
    public function scopeBySize($query, $size)
    {
        return $query->where('ukuran_sampah', $size);
    }

    /**
     * Accessor: Get total points
     */
    public function getTotalPoinAttribute()
    {
        return $this->poin_per_sampah;
    }
}