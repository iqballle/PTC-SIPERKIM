<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perumahans', function (Blueprint $table) {
            if (!Schema::hasColumn('perumahans', 'spesifikasi')) {
                $table->text('spesifikasi')->nullable()->after('fasilitas');
            }

            if (!Schema::hasColumn('perumahans', 'tabel_angsuran')) {
                $table->string('tabel_angsuran', 255)->nullable()->after('spesifikasi');
            }

            if (!Schema::hasColumn('perumahans', 'denah_rumah')) {
                $table->string('denah_rumah', 255)->nullable()->after('tabel_angsuran');
            }

            if (!Schema::hasColumn('perumahans', 'dokumen_foto')) {
                // simpan JSON string (bisa TEXT)
                $table->text('dokumen_foto')->nullable()->after('gallery');
            }
        });
    }

    public function down(): void
    {
        Schema::table('perumahans', function (Blueprint $table) {
            $cols = ['spesifikasi', 'tabel_angsuran', 'denah_rumah', 'dokumen_foto'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('perumahans', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
