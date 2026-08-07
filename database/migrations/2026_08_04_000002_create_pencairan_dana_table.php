<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pencairan_dana', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_permohonan', 50)->unique();
            $table->foreignId('apbdes_id')->nullable()->constrained('apbdes');
            $table->foreignId('musrenbang_id')->nullable()->constrained('musrenbang');
            $table->string('nama_kegiatan', 500);
            $table->decimal('jumlah_pencairan', 18, 2);
            $table->string('sumber_dana', 100);
            $table->string('jenis_pencairan', 50)->comment('rutin, insidentil, proyek, lainnya');
            $table->string('status_pencairan', 20)->default('draft')->comment('draft, diajukan, diverifikasi, disetujui, diproses, dicairkan, ditolak');
            $table->foreignId('pemohon_id')->constrained('users');
            $table->foreignId('verifikator_keuangan_id')->nullable()->constrained('users');
            $table->foreignId('penandatangan_id')->nullable()->constrained('users');
            $table->foreignId('bendahara_id')->nullable()->constrained('users');
            $table->timestamp('tanggal_pengajuan')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamp('tanggal_persetujuan')->nullable();
            $table->timestamp('tanggal_pencairan')->nullable();
            $table->text('catatan_pencairan')->nullable();
            $table->string('metode_pembayaran', 50)->nullable()->comment('transfer, tunai, cek, lainnya');
            $table->string('nama_bank', 100)->nullable();
            $table->string('nomor_rekening', 50)->nullable();
            $table->string('atas_nama', 200)->nullable();
            $table->text('bukti_pembayaran')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pencairan_dana_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pencairan_dana_id')->constrained('pencairan_dana')->cascadeOnDelete();
            $table->string('nama_dokumen', 200);
            $table->string('tipe_dokumen', 50)->comment('proposal, spm, spj, bukti, lain');
            $table->string('path_dokumen', 500);
            $table->timestamps();
        });

        Schema::create('pencairan_dana_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pencairan_dana_id')->constrained('pencairan_dana')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('aksi', 100);
            $table->text('deskripsi');
            $table->json('data_sebelum')->nullable();
            $table->json('data_sesudah')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pencairan_dana_log');
        Schema::dropIfExists('pencairan_dana_dokumen');
        Schema::dropIfExists('pencairan_dana');
    }
};