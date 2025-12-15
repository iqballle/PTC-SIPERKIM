// database/migrations/2025_01_01_000000_create_permohonan_nota_dinas_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permohonan_nota_dinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('perumahan_id')->constrained('perumahans')->cascadeOnDelete();
        
            $table->string('nama_pengembang');
            $table->string('nama_perumahan');
            $table->string('telepon')->nullable();
            $table->string('alamat_perumahan');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->text('keterangan_tambahan')->nullable();
            $table->string('status')->default('pending');
        
            // path file masing-masing dokumen
            $table->string('surat_permohonan')->nullable();
            $table->string('profil_perusahaan')->nullable();
            $table->string('ktp_direktur')->nullable();
            $table->string('npwp_perusahaan')->nullable();
            $table->string('akte_pendirian')->nullable();
            $table->string('surat_kesiapan_psu')->nullable();
            $table->string('surat_tidak_sengketa')->nullable();
            $table->string('pkkpr')->nullable();
            $table->string('nib_kbli')->nullable();
            $table->string('peil_banjir')->nullable();
            $table->string('alas_hak')->nullable();
            $table->string('bast_tahap_pengembangan')->nullable();
            $table->string('siteplan_a3')->nullable();
            $table->string('peta_lokasi')->nullable();
            $table->string('site_plan')->nullable();
            $table->string('kontur_tanah')->nullable();
            $table->string('rencana_jalan')->nullable();
            $table->string('rencana_drainase')->nullable();
            $table->string('rencana_rth')->nullable();
            $table->string('rencana_air_bersih')->nullable();
            $table->string('rencana_sanitasi')->nullable();
            $table->string('rencana_fasum_fasos')->nullable();
        
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_nota_dinas');
    }
};
