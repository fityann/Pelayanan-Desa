<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('musrenbang', function (Blueprint $table) {
            $table->id();
            $table->string('tahun', 4);
            $table->string('judul_kegiatan', 500);
            $table->text('deskripsi_kegiatan');
            $table->foreignId('wilayah_id')->nullable();
            $table->string('jenis_kegiatan', 100)->comment('fisik, non-fisik, sosial, ekonomi');
            $table->decimal('estimasi_biaya', 18, 2)->default(0);
            $table->string('sumber_dana', 100)->default('APBDes');
            $table->string('prioritas', 20)->default('rendah')->comment('rendah, sedang, tinggi, sangat_tinggi');
            $table->integer('jumlah_pengusul')->default(0);
            $table->integer('jumlah_pendukung')->default(0);
            $table->string('status_usulan', 20)->default('diusulkan')->comment('diusulkan, diverifikasi, direview, disetujui, ditolak');
            $table->foreignId('pengusul_id')->nullable()->constrained('users');
            $table->foreignId('verifikator_id')->nullable()->constrained('users');
            $table->foreignId('reviewer_id')->nullable()->constrained('users');
            $table->text('catatan_review')->nullable();
            $table->timestamp('tanggal_musrenbang')->nullable();
            $table->string('hasil_musrenbang', 20)->nullable()->comment('layak, revisi, ditunda, ditolak');
            $table->decimal('alokasi_anggaran', 18, 2)->nullable();
            $table->timestamp('tanggal_realisasi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('musrenbang_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('musrenbang_id')->constrained('musrenbang')->cascadeOnDelete();
            $table->string('nama_dokumen', 200);
            $table->string('tipe_dokumen', 50)->comment('proposal, foto, surat, lain');
            $table->string('path_dokumen', 500);
            $table->timestamps();
        });

        Schema::create('musrenbang_suara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('musrenbang_id')->constrained('musrenbang')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('tipe_suara', 20)->comment('dukung, tolak, abstain');
            $table->text('alasan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('musrenbang_suara');
        Schema::dropIfExists('musrenbang_dokumen');
        Schema::dropIfExists('musrenbang');
    }
};