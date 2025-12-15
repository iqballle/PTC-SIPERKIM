<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perumahans', function (Blueprint $table) {
            // Pemilik (developer) & verifikator (dinas)
            if (!Schema::hasColumn('perumahans', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')
                      ->constrained()->nullOnDelete();
            }

            // Detail baru
            if (!Schema::hasColumn('perumahans', 'lokasi_google_map')) {
                $table->string('lokasi_google_map')->nullable()->after('lokasi');
            }
            if (!Schema::hasColumn('perumahans', 'nama_perusahaan')) {
                $table->string('nama_perusahaan')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('perumahans', 'nama_developer')) {
                $table->string('nama_developer')->nullable()->after('nama_perusahaan');
            }
            if (!Schema::hasColumn('perumahans', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('nama_developer');
            }
            if (!Schema::hasColumn('perumahans', 'telepon')) {
                $table->string('telepon', 25)->nullable()->after('deskripsi');
            }

            // Media baru
            if (!Schema::hasColumn('perumahans', 'cover')) {
                $table->string('cover')->nullable()->after('image'); // kita tetap simpan kolom lama 'image'
            }
            if (!Schema::hasColumn('perumahans', 'gallery')) {
                $table->json('gallery')->nullable()->after('cover');
            }
            if (!Schema::hasColumn('perumahans', 'dokumen_foto')) {
                $table->json('dokumen_foto')->nullable()->after('gallery');
            }

            // Status verifikasi (biarkan kolom 'status' lama tetap ada; pastikan nilai yang dipakai: pending|disetujui|ditolak)
            if (!Schema::hasColumn('perumahans', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('perumahans', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('approved_at')
                      ->constrained('users')->nullOnDelete();
            }
        });

        // Backfill ringan: salin nilai image lama ke cover (jika kolom image sudah terisi)
        try {
            DB::table('perumahans')
              ->whereNotNull('image')
              ->whereNull('cover')
              ->update(['cover' => DB::raw('image')]);
        } catch (\Throwable $e) {
            // abaikan jika DB tidak mengizinkan raw update (aman)
        }
    }

    public function down(): void
    {
        Schema::table('perumahans', function (Blueprint $table) {
            // Hapus hanya kolom yang kita tambahkan (biarkan kolom lama tetap ada)
            if (Schema::hasColumn('perumahans', 'approved_by')) {
                $table->dropConstrainedForeignId('approved_by');
            }
            if (Schema::hasColumn('perumahans', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
            foreach (['lokasi_google_map','nama_perusahaan','nama_developer','deskripsi','telepon','cover','gallery','dokumen_foto','approved_at'] as $col) {
                if (Schema::hasColumn('perumahans', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};