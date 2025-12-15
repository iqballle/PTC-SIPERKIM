<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::table('perumahans', function (Blueprint $table) {
            $table->string('unit_status')->default('tersedia')->after('status_verifikasi'); 
            // sesuaikan "after" dengan kolom yg sudah ada, kalau tidak ada status_verifikasi, hapus ->after(...)
        });
    }

    public function down()
    {
        Schema::table('perumahans', function (Blueprint $table) {
            $table->dropColumn('unit_status');
        });
    }

};
