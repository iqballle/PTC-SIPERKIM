<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('perumahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // developer pemilik
            $table->string('nama');
            $table->string('lokasi_google_map')->nullable(); // link GMap
            $table->string('nama_perusahaan')->nullable();
            $table->string('nama_developer')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('telepon')->nullable();

            $table->string('cover')->nullable();          // satu cover
            $table->json('gallery')->nullable();          // max 5 foto
            $table->json('dokumen_foto')->nullable();     // max 3 foto dokumen (IMB, dsb)

            $table->enum('status', ['pending','disetujui','ditolak'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('perumahans');
    }
};