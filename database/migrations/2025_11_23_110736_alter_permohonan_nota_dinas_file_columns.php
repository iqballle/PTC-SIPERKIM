<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonan_nota_dinas', function (Blueprint $table) {
            // Semua kolom path file (string, nullable)
            $table->string('surat_permohonan')->nullable()->after('status');
            $table->string('profil_perusahaan')->nullable()->after('surat_permohonan');
            $table->string('ktp_direktur')->nullable()->after('profil_perusahaan');
            $table->string('npwp_perusahaan')->nullable()->after('ktp_direktur');
            $table->string('akte_pendirian')->nullable()->after('npwp_perusahaan');
            $table->string('surat_kesiapan_psu')->nullable()->after('akte_pendirian');
            $table->string('surat_tidak_sengketa')->nullable()->after('surat_kesiapan_psu');
            $table->string('pkkpr')->nullable()->after('surat_tidak_sengketa');
            $table->string('nib_kbli')->nullable()->after('pkkpr');
            $table->string('peil_banjir')->nullable()->after('nib_kbli');
            $table->string('alas_hak')->nullable()->after('peil_banjir');
            $table->string('bast_tahap_pengembangan')->nullable()->after('alas_hak');
            $table->string('siteplan_a3')->nullable()->after('bast_tahap_pengembangan');
            $table->string('peta_lokasi')->nullable()->after('siteplan_a3');
            $table->string('site_plan')->nullable()->after('peta_lokasi');
            $table->string('kontur_tanah')->nullable()->after('site_plan');
            $table->string('rencana_jalan')->nullable()->after('kontur_tanah');
            $table->string('rencana_drainase')->nullable()->after('rencana_jalan');
            $table->string('rencana_rth')->nullable()->after('rencana_drainase');
            $table->string('rencana_air_bersih')->nullable()->after('rencana_rth');
            $table->string('rencana_sanitasi')->nullable()->after('rencana_air_bersih');
            $table->string('rencana_fasum_fasos')->nullable()->after('rencana_sanitasi');
        });
    }

    public function down(): void
    {
        Schema::table('permohonan_nota_dinas', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};
