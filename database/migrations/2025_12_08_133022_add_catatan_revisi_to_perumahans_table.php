<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('perumahans', function (Blueprint $table) {
            // sesuaikan posisi 'after' dengan kolom yang sudah ada
            $table->text('catatan_revisi')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('perumahans', function (Blueprint $table) {
            $table->dropColumn('catatan_revisi');
        });
    }
};
