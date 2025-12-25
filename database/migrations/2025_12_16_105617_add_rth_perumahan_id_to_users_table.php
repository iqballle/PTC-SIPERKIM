<?php

// database/migrations/xxxx_add_rth_perumahan_id_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('users', function (Blueprint $table) {
      $table->unsignedBigInteger('rth_perumahan_id')->nullable()->after('rth_device_id');
      // optional FK:
      // $table->foreign('rth_perumahan_id')->references('id')->on('perumahans')->nullOnDelete();
    });
  }

  public function down(): void {
    Schema::table('users', function (Blueprint $table) {
      // optional drop FK dulu kalau dipakai
      // $table->dropForeign(['rth_perumahan_id']);
      $table->dropColumn('rth_perumahan_id');
    });
  }
};