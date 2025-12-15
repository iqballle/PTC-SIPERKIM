<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermohonanNotaDinas extends Model
{
    use HasFactory;

    protected $table = 'permohonan_nota_dinas';

    protected $fillable = [
        'user_id',
        'perumahan_id',
        'nama_pengembang',
        'nama_perumahan',
        'telepon',
        'alamat_perumahan',
        'kelurahan',
        'kecamatan',
        'keterangan_tambahan',
        'status',
        'catatan_dinas',
        'verified_at',
        'verified_by',

        'surat_permohonan',
        'profil_perusahaan',
        'ktp_direktur',
        'npwp_perusahaan',
        'akte_pendirian',
        'surat_kesiapan_psu',
        'surat_tidak_sengketa',
        'pkkpr',
        'nib_kbli',
        'peil_banjir',
        'alas_hak',
        'bast_tahap_pengembangan',
        'siteplan_a3',
        'peta_lokasi',
        'site_plan',
        'kontur_tanah',
        'rencana_jalan',
        'rencana_drainase',
        'rencana_rth',
        'rencana_air_bersih',
        'rencana_sanitasi',
        'rencana_fasum_fasos',
    ];

    // ✅ ini yang penting
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function perumahan()
    {
        return $this->belongsTo(Perumahan::class, 'perumahan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
