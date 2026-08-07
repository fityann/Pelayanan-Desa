<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            // Add new columns for QR code and location tracking
            $table->string('tiket_id', 50)->nullable()->unique()->after('id');
            $table->string('nama_pelapor', 100)->nullable()->after('deskripsi');
            $table->string('whatsapp', 20)->nullable()->after('nama_pelapor');
            $table->string('lokasi_qr', 500)->nullable()->after('sumber_akses');
            $table->decimal('latitude', 10, 6)->nullable()->after('lokasi_qr');
            $table->decimal('longitude', 10, 6)->nullable()->after('latitude');
            $table->string('rt', 3)->nullable()->after('longitude');
            $table->string('rw', 3)->nullable()->after('rt');
        });
    }

    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn(['tiket_id', 'nama_pelapor', 'whatsapp', 'lokasi_qr', 'latitude', 'longitude', 'rt', 'rw']);
        });
    }
};