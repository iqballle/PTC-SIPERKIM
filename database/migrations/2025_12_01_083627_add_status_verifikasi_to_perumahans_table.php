<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perumahans', function (Blueprint $table) {
            // status: pending | disetujui | revisi
            $table->string('status_verifikasi', 20)
                ->default('pending')
                ->after('user_id');

            // catatan dari dinas kalau perlu revisi
            $table->text('catatan_verifikasi')
                ->nullable()
                ->after('status_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('perumahans', function (Blueprint $table) {
            $table->dropColumn(['status_verifikasi', 'catatan_verifikasi']);
        });
    }
};

