<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->cascadeOnDelete();
            $table->string('nomor_surat', 100)->nullable()->unique();
            $table->string('status', 50)->default('diajukan')->comment('diajukan, diverifikasi_admin, ditolak, disetujui_kades, menunggu_ttd_fisik, selesai');
            $table->boolean('butuh_ttd_fisik')->default(true)->comment('true = perlu TTD basah Kepala Desa (alur cetak draft)');
            $table->text('keterangan')->nullable();
            $table->text('alasan_ditolak')->nullable();
            $table->string('file_pendukung')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('tanggal_diajukan')->useCurrent();
            $table->timestamp('tanggal_disetujui')->nullable();
            $table->timestamp('tanggal_ttd_fisik')->nullable();
            $table->timestamp('tanggal_diambil')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surats');
    }
};
