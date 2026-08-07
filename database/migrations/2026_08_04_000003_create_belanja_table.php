<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('belanja', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_belanja', 50)->unique();
            $table->foreignId('pencairan_dana_id')->constrained('pencairan_dana');
            $table->string('jenis_belanja', 50)->comment('barang, jasa, modal, lainnya');
            $table->string('nama_barang_jasa', 500);
            $table->integer('kuantitas')->default(1);
            $table->string('satuan', 50);
            $table->decimal('harga_satuan', 18, 2);
            $table->decimal('total_harga', 18, 2);
            $table->text('spesifikasi')->nullable();
            $table->string('metode_pengadaan', 50)->default('langsung')->comment('langsung, tender, seleksi, lainnya');
            $table->string('penyedia', 200)->nullable();
            $table->string('status_belanja', 20)->default('draft')->comment('draft, diajukan, diproses, dikirim, diterima, selesai, ditolak');
            $table->foreignId('pemohon_id')->constrained('users');
            $table->foreignId('penyedia_id')->nullable()->constrained('users');
            $table->foreignId('penerima_id')->nullable()->constrained('users');
            $table->timestamp('tanggal_pengajuan')->nullable();
            $table->timestamp('tanggal_persetujuan')->nullable();
            $table->timestamp('tanggal_pembelian')->nullable();
            $table->timestamp('tanggal_pengiriman')->nullable();
            $table->timestamp('tanggal_penerimaan')->nullable();
            $table->text('catatan_penerimaan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('belanja_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('belanja_id')->constrained('belanja')->cascadeOnDelete();
            $table->string('nama_dokumen', 200);
            $table->string('tipe_dokumen', 50)->comment('permintaan, penawaran, kontrak, invoice, kwitansi, penerimaan, lain');
            $table->string('path_dokumen', 500);
            $table->timestamps();
        });

        Schema::create('belanja_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('belanja_id')->constrained('belanja')->cascadeOnDelete();
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
        Schema::dropIfExists('belanja_log');
        Schema::dropIfExists('belanja_dokumen');
        Schema::dropIfExists('belanja');
    }
};