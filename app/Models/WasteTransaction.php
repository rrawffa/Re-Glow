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
    ];

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
        return $this->hasMany(RiwayatSampah::class, 'id_tSampah', 'id_tSampah');
    }

    public function jadwalPengambilan()
    {
        return $this->hasOne(JadwalPengambilan::class, 'id_transaksi', 'id_tSampah');
    }

    public function getStatus()
    {
        return $this->status;
    }

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
    }
}

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

    public function transaksi()
    {
        return $this->belongsTo(TransaksiSampah::class, 'id_tSampah', 'id_tSampah');
    }

    public function hitungPoin()
    {
        // Sistem poin berdasarkan ukuran
        $poinMap = [
            'Large' => 50,
            'Medium' => 30,
            'Small' => 15
        ];
        
        return $poinMap[$this->ukuran_sampah] ?? 0;
    }

    public function getJenis()
    {
        return $this->jenis_sampah;
    }

    public function getUkuran()
    {
        return $this->ukuran_sampah;
    }
}

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
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiSampah::class, 'id_tSampah', 'id_tSampah');
    }
}

class JadwalLogistik extends Model
{
    protected $table = 'jadwallogistik';
    protected $primaryKey = 'id_jadwal';
    
    protected $fillable = [
        'id_user',
        'id_drop_point',
        'tanggal_jemput',
        'status_jadwal'
    ];

    protected $casts = [
        'tanggal_jemput' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function dropPoint()
    {
        return $this->belongsTo(DropPoint::class, 'id_drop_point', 'id_drop_point');
    }
}

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
    ];

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
}

class RiwayatPengambilan extends Model
{
    protected $table = 'riwayatpengambilan';
    protected $primaryKey = 'id_riwayat_peng';
    
    protected $fillable = [
        'id_drop_point',
        'id_user',
        'tanggal_ambil'
    ];

    protected $casts = [
        'tanggal_ambil' => 'datetime',
    ];

    public function dropPoint()
    {
        return $this->belongsTo(DropPoint::class, 'id_drop_point', 'id_drop_point');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function bukti()
    {
        return $this->hasOne(BuktiPengambilan::class, 'id_riwayat_peng', 'id_riwayat_peng');
    }
}

class BuktiPengambilan extends Model
{
    protected $table = 'buktipengambilan';
    protected $primaryKey = 'id_bukti';
    
    protected $fillable = [
        'id_riwayat_peng',
        'foto_bukti',
        'jumlah_item',
        'catatan'
    ];

    public function riwayat()
    {
        return $this->belongsTo(RiwayatPengambilan::class, 'id_riwayat_peng', 'id_riwayat_peng');
    }
}