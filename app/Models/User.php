<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'pengguna';
    protected $primaryKey = 'id_user';
    public $timestamps = true;
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'email',
        'password',
        'no_hp',
        'role',
        'status_akun',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Boot function untuk auto-hash password
    protected static function boot()
    {
        parent::boot();

        // Hash password saat membuat user baru
        static::creating(function ($user) {
            if (!empty($user->password) && !Hash::isHashed($user->password)) {
                $user->password = Hash::make($user->password);
            }
        });

        // Hash password saat update jika password berubah
        static::updating(function ($user) {
            if ($user->isDirty('password') && !empty($user->password)) {
                if (!Hash::isHashed($user->password)) {
                    $user->password = Hash::make($user->password);
                }
            }
        });
    }

    /**
     * Helper methods
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isLogistik()
    {
        return $this->role === 'tim_logistik';
    }

    public function isPengguna()
    {
        return $this->role === 'pengguna';
    }

    public function isActive()
    {
        return $this->status_akun === 'active';
    }

    public function hasColumn($column)
    {
        return in_array($column, $this->getConnection()->getSchemaBuilder()->getColumnListing($this->table));
    }

    public function getDashboardRoute()
    {
        switch ($this->role) {
            case 'admin':
                return route('admin.dashboard');
            case 'tim_logistik':
                return route('logistik.dashboard');
            case 'pengguna':
            default:
                return route('user.dashboard');
        }
    }
      public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class, 'user_id', 'id_user');
    }


    /**
     * Validation rules untuk update profile
     */
    public static function updateProfileRules($userId)
    {
        return [
            'username' => 'nullable|string|min:3|max:100|unique:pengguna,username,' . $userId . ',id_user',
            'email' => 'nullable|email|ends_with:@gmail.com|unique:pengguna,email,' . $userId . ',id_user',
            'no_hp' => 'nullable|string|max:20',
            'current_password' => 'required_if:email,no_hp|nullable|min:8',
        ];
    }

    /**
     * Validation messages untuk update profile
     */
    public static function updateProfileMessages()
    {
        return [
            'username.min' => 'Username minimal 3 karakter',
            'username.max' => 'Username maksimal 100 karakter',
            'username.unique' => 'Username sudah digunakan',
            'email.email' => 'Format email tidak valid',
            'email.ends_with' => 'Email harus menggunakan @gmail.com',
            'email.unique' => 'Email sudah terdaftar',
            'no_hp.max' => 'Nomor telepon maksimal 20 karakter',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar harus JPG atau PNG',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
            'current_password.required_if' => 'Password saat ini diperlukan untuk mengubah email atau nomor telepon',
            'current_password.min' => 'Password minimal 8 karakter',
        ];
    }

    /**
     * Check if user provided correct current password
     */
    public function checkCurrentPassword($password)
    {
        return Hash::check($password, $this->password);
    }
}