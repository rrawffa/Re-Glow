<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    protected $table = 'point_transactions';

    protected $primaryKey = 'id';

   protected $fillable = ['user_id', 'type', 'points', 'description'];


   public function user()
{
    return $this->belongsTo(User::class);
}

}
