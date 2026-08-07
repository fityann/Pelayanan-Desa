<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // user_id harus nullable agar warga yang scan QR tanpa login bisa mengadu
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });

        // Tambahkan kolom created_by untuk relasi User ke QR code
        Schema::table('rt_qr_codes', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('status');
        });

        // Tambahkan FK kepala_keluarga_id untuk normalisasi data KK
        Schema::table('keluarga', function (Blueprint $table) {
            $table->foreignId('kepala_keluarga_id')->nullable()->after('kepala_keluarga');
        });
    }

    public function down(): void
    {
        Schema::table('keluarga', function (Blueprint $table) {
            $table->dropColumn('kepala_keluarga_id');
        });

        Schema::table('rt_qr_codes', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });

        Schema::table('pengaduans', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};