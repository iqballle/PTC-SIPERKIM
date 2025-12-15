<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perumahans', function (Blueprint $table) {
            $table->integer('tahun_pembangunan')->nullable()->after('tipe');
            $table->integer('luas_tanah')->nullable()->after('tahun_pembangunan');      // m2
            $table->integer('jumlah_unit')->nullable()->after('luas_tanah');
            $table->integer('luas_bangunan')->nullable()->after('jumlah_unit');        // m2
        });
    }

    public function down(): void
    {
        Schema::table('perumahans', function (Blueprint $table) {
            $table->dropColumn([
                'tahun_pembangunan',
                'luas_tanah',
                'jumlah_unit',
                'luas_bangunan',
            ]);
        });
    }
};
