<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TransaksiSampah;
use App\Models\User;

class PointTransaction extends Model
{
    protected $table = 'point_transactions';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'id_tSampah',
        'points',
        'type',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function transaksi()
    {
        return $this->belongsTo(TransaksiSampah::class, 'id_tSampah', 'id_tSampah');
    }
}
