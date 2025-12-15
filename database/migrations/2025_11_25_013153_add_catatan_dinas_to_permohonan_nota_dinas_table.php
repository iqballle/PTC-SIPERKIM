<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonan_nota_dinas', function (Blueprint $table) {
            $table->text('catatan_dinas')->nullable()->after('status');
            $table->timestamp('verified_at')->nullable()->after('catatan_dinas');
            $table->string('verified_by')->nullable()->after('verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('permohonan_nota_dinas', function (Blueprint $table) {
            $table->dropColumn(['catatan_dinas', 'verified_at', 'verified_by']);
        });
    }
};
