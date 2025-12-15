<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Perumahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'lokasi',
        'lokasi_google_map',
        'nama_perusahaan',
        'nama_developer',
        'deskripsi',
        'telepon',
        'harga',
        'tipe',
        'tahun_pembangunan',
        'luas_tanah',
        'jumlah_unit',
        'luas_bangunan',
        'fasilitas',

        // tambahan
        'spesifikasi',
        'tabel_angsuran',
        'denah_rumah',

        'cover',
        'gallery',
        'dokumen_foto',

        'status',
        'status_unit',
        'approved_at',
        'approved_by',
        'status_verifikasi',
        'catatan_verifikasi',
        'unit_status',
        'catatan_revisi',
    ];

    protected $casts = [
        'gallery'      => 'array',
        'dokumen_foto' => 'array',
        'approved_at'  => 'datetime',
    ];

    protected $appends = [
        'cover_url',
        'gallery_urls',
        'dokumen_foto_urls',
        'tabel_angsuran_url',
        'denah_url',
    ];

    /* ================= RELATION ================= */

    // Developer pemilik perumahan
    public function developer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ✅ Dinas yang menyetujui (approved_by menyimpan ID user dinas)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /* ================= ACCESSOR GAMBAR ================= */

    // COVER
    public function getCoverUrlAttribute()
    {
        if (!$this->cover) return null;
        if (str_starts_with($this->cover, 'http')) return $this->cover;

        return Storage::url($this->cover);
    }

    // GALLERY
    public function getGalleryUrlsAttribute()
    {
        if (!$this->gallery) return [];

        $paths = is_array($this->gallery)
            ? $this->gallery
            : json_decode($this->gallery, true);

        if (!$paths) return [];

        return array_map(function ($path) {
            return str_starts_with($path, 'http')
                ? $path
                : Storage::url($path);
        }, $paths);
    }

    // DOKUMEN FOTO
    public function getDokumenFotoUrlsAttribute()
    {
        if (!$this->dokumen_foto) return [];

        $paths = is_array($this->dokumen_foto)
            ? $this->dokumen_foto
            : json_decode($this->dokumen_foto, true);

        if (!$paths) return [];

        return array_map(function ($path) {
            return str_starts_with($path, 'http')
                ? $path
                : Storage::url($path);
        }, $paths);
    }

    // TABEL ANGSURAN
    public function getTabelAngsuranUrlAttribute()
    {
        if (!$this->tabel_angsuran) return null;
        if (str_starts_with($this->tabel_angsuran, 'http')) return $this->tabel_angsuran;

        return Storage::url($this->tabel_angsuran);
    }

    // DENAH RUMAH
    public function getDenahUrlAttribute()
    {
        if (!$this->denah_rumah) return null;
        if (str_starts_with($this->denah_rumah, 'http')) return $this->denah_rumah;

        return Storage::url($this->denah_rumah);
    }
}