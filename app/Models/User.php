<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'users';

    protected $primaryKey = 'id_user';
    public $timestamps = true;
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

    /**
     * Validation rules
     */
    public static function validationRules()
    {
        return [
            'username' => 'required|string|min:3|max:100|unique:pengguna,username',
            'email' => 'required|email|ends_with:@gmail.com|unique:pengguna,email',
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
            'password_confirmation' => 'required|same:password',
            'no_hp' => 'nullable|string|max:20',
            'role' => 'in:pengguna,admin,tim_logistik',
        ];
    }

    /**
     * Validation messages
     */
    public static function validationMessages()
    {
        return [
            'username.required' => 'Username tidak boleh kosong',
            'username.min' => 'Username minimal 3 karakter',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.ends_with' => 'Email harus menggunakan @gmail.com',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter',
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar dan 1 angka',
            'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong',
            'password_confirmation.same' => 'Password tidak cocok',
        ];
    }
}
