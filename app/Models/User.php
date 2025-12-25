<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi mass-assignment.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',              // developer / dinas / dll
        'phone',             // nomor telepon
        'photo_path',

        // ✅ RTH
        'rth_device_id',
        'rth_perumahan_id',  // ✅ tambah: perumahan yang dipilih untuk RTH
    ];

    /**
     * Disembunyikan saat di-cast ke array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // =========================================================
    // ✅ RELATIONSHIPS
    // =========================================================

    /**
     * ✅ Perumahan yang dipilih untuk fitur RTH (1 user -> 1 perumahan terpilih)
     * Pastikan kolom users.rth_perumahan_id ada, dan mengarah ke perumahans.id
     */
    public function perumahanRth()
    {
        return $this->belongsTo(\App\Models\Perumahan::class, 'rth_perumahan_id');
    }

    // =========================================================
    // ✅ HELPERS
    // =========================================================

    public function isDeveloper(): bool
    {
        return $this->role === 'developer';
    }

    public function isDinas(): bool
    {
        return $this->role === 'dinas';
    }

    /**
     * Accessor: $user->photo_url
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }

        return asset('images/default-avatar.png');
    }
}