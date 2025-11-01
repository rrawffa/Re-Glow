<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengguna';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'no_hp',
        'role',
        'status_akun',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot function for using model events
     */
    protected static function boot()
    {
        parent::boot();

        // Hash password automatically when creating user
        static::creating(function ($user) {
            if (!empty($user->password)) {
                $user->password = Hash::make($user->password);
            }
        });

        // Hash password automatically when updating user
        static::updating(function ($user) {
            if ($user->isDirty('password') && !empty($user->password)) {
                $user->password = Hash::make($user->password);
            }
        });
    }

    /**
     * Relationships
     */

    // Relasi ke tabel session
    public function sessions()
    {
        return $this->hasMany(Session::class, 'id_user', 'id_user');
    }

    // Relasi ke tabel notifikasi
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_user', 'id_user');
    }

    // Relasi ke tabel verifikasi_akun
    public function verifikasiAkun()
    {
        return $this->hasMany(VerifikasiAkun::class, 'id_user', 'id_user');
    }

    // Relasi ke tabel administrator (jika role admin)
    public function administrator()
    {
        return $this->hasOne(Administrator::class, 'id_user', 'id_user');
    }

    /**
     * Helper methods
     */

    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Check if user is tim logistik
    public function isLogistik()
    {
        return $this->role === 'tim_logistik';
    }

    // Check if user is regular user (pengguna)
    public function isPengguna()
    {
        return $this->role === 'pengguna';
    }

    // Check if account is active
    public function isActive()
    {
        return $this->status_akun === 'active';
    }

    // Get user's dashboard route based on role
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
                'regex:/[A-Z]/',  // Minimal 1 huruf besar
                'regex:/[0-9]/',  // Minimal 1 angka
            ],
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
        ];
    }
}
