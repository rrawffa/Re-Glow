<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'name','brand','description','required_points','expiration_date','stock','image_url'
    ];

    protected $dates = ['expiration_date'];

    public function isExpired()
    {
        return $this->expiration_date && $this->expiration_date->isPast();
    }

    public function isOutOfStock()
    {
        return $this->stock <= 0;
    }
}
