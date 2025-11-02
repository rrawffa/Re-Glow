<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatistikEdukasi extends Model
{
    use HasFactory;

    protected $table = 'statistikedukasi';
    protected $primaryKey = 'id_statistik';
    public $timestamps = false;

    protected $fillable = [
        'id_konten',
        'total_view',
        'total_suka',
        'total_membantu',
        'total_menarik',
        'total_inspiratif',
        'last_updated'
    ];

    protected $casts = [
        'last_updated' => 'datetime',
        'total_view' => 'integer',
        'total_suka' => 'integer',
        'total_membantu' => 'integer',
        'total_menarik' => 'integer',
        'total_inspiratif' => 'integer'
    ];

    // Relasi ke education content
    public function education()
    {
        return $this->belongsTo(Education::class, 'id_konten', 'id_konten');
    }

    // Method untuk update reaksi
public function updateReaction($tipeReaksi, $action = 'add')
    {
        $column = 'total_' . $tipeReaksi;
        
        if ($action === 'add') {
            $this->increment($column);
        } else if ($action === 'remove' && $this->{$column} > 0) {
            $this->decrement($column);
        }
        
        $this->update(['last_updated' => now()]);
    }

    // Override method increment untuk handle last_updated
    public function increment($column, $amount = 1, array $extra = [])
    {
        $extra['last_updated'] = now();
        return parent::increment($column, $amount, $extra);
    }

    // Override method decrement untuk handle last_updated
    public function decrement($column, $amount = 1, array $extra = [])
    {
        $extra['last_updated'] = now();
        return parent::decrement($column, $amount, $extra);
    }

    public function getTotalReactionsAttribute()
    {
        return $this->total_suka + 
               $this->total_membantu + 
               $this->total_menarik + 
               $this->total_inspiratif;
    }
}