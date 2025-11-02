<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropPoint extends Model
{
    protected $table = 'drop_points';
    protected $primaryKey = 'id_drop_point';
    
    protected $fillable = [
        'nama_lokasi',
        'koordinat',
        'kapasitas_sampah',
        'alamat'
    ];

    protected $casts = [
        'kapasitas_sampah' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function transactions()
    {
        return $this->hasMany(TransaksiSampah::class, 'id_drop_point', 'id_drop_point');
    }

    public function jadwalLogistik()
    {
        return $this->hasMany(JadwalLogistik::class, 'id_drop_point', 'id_drop_point');
    }

    public function jadwalPengambilan()
    {
        return $this->hasMany(JadwalPengambilan::class, 'id_drop_point', 'id_drop_point');
    }

    public function riwayatPengambilan()
    {
        return $this->hasMany(RiwayatPengambilan::class, 'id_drop_point', 'id_drop_point');
    }

    /**
     * Get latitude from koordinat
     */
    public function getLatitude()
    {
        $coords = explode(',', $this->koordinat);
        return isset($coords[0]) ? trim($coords[0]) : '0';
    }

    /**
     * Get longitude from koordinat
     */
    public function getLongitude()
    {
        $coords = explode(',', $this->koordinat);
        return isset($coords[1]) ? trim($coords[1]) : '0';
    }

    /**
     * Get coordinates as array
     */
    public function getCoordinatesArray()
    {
        return [
            'lat' => $this->getLatitude(),
            'lng' => $this->getLongitude()
        ];
    }

    /**
     * Check kapasitas
     */
    public function cekKapasitas()
    {
        return $this->kapasitas_sampah > 0;
    }

    /**
     * Check if full
     */
    public function isFull()
    {
        return $this->kapasitas_sampah <= 0;
    }

    /**
     * Get kapasitas percentage
     */
    public function getKapasitasPercentage()
    {
        $maxKapasitas = 1000; // Assume max capacity
        return ($this->kapasitas_sampah / $maxKapasitas) * 100;
    }

    /**
     * Get total transactions
     */
    public function getTotalTransactions()
    {
        return $this->transactions()->count();
    }

    /**
     * Get pending pickups count
     */
    public function getPendingPickupsCount()
    {
        return $this->jadwalPengambilan()
                    ->whereIn('status', ['Pending', 'Dikonfirmasi'])
                    ->count();
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('kapasitas_sampah', '>', 0);
    }

    public function scopeNearby($query, $lat, $lng, $radius = 10)
    {
        // Simple distance calculation (not precise, for demo purposes)
        return $query->whereRaw("
            (6371 * acos(cos(radians(?)) 
            * cos(radians(SUBSTRING_INDEX(koordinat, ',', 1))) 
            * cos(radians(SUBSTRING_INDEX(koordinat, ',', -1)) - radians(?)) 
            + sin(radians(?)) 
            * sin(radians(SUBSTRING_INDEX(koordinat, ',', 1))))) < ?
        ", [$lat, $lng, $lat, $radius]);
    }

    public function scopeWithCapacity($query, $minCapacity = 0)
    {
        return $query->where('kapasitas_sampah', '>=', $minCapacity);
    }

    /**
     * Accessor: Short address
     */
    public function getShortAddressAttribute()
    {
        return strlen($this->alamat) > 50 
            ? substr($this->alamat, 0, 50) . '...' 
            : $this->alamat;
    }

    /**
     * Accessor: Display name with capacity
     */
    public function getDisplayNameAttribute()
    {
        $status = $this->isFull() ? ' (Full)' : '';
        return $this->nama_lokasi . $status;
    }
}