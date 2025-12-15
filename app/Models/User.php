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
        'role',        // developer / dinas / dll
        'phone',       // nomor telepon
        'photo_path',  // path foto di storage
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

    /**
     * Helper role (opsional, kalau mau dipakai nanti).
     */
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
     * Menghasilkan URL foto (atau default avatar kalau belum ada).
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            // Asumsi: sudah menjalankan `php artisan storage:link`
            return asset('storage/' . $this->photo_path);
        }

        // fallback ke gambar default
        return asset('images/default-avatar.png');
    }
}