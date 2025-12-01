<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'brand',
        'description',
        'required_points',
        'expiration_date',
        'stock',
        'image_url'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_voucher', 'voucher_id', 'id_user')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
