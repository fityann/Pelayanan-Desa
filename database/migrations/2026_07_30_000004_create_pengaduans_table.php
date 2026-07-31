<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kategori', 100);
            $table->string('judul', 200);
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->string('status', 50)->default('diterima')->comment('diterima, diproses, selesai');
            $table->text('tanggapan')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('tanggal_diterima')->useCurrent();
            $table->timestamp('tanggal_diproses')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
