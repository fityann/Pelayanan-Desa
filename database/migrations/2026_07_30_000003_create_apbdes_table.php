<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apbdes', function (Blueprint $table) {
            $table->id();
            $table->string('tahun', 4)->default('2026');
            $table->string('kategori', 50)->comment('Pendapatan, Belanja, Pembiayaan');
            $table->string('bidang', 200)->nullable();
            $table->string('sub_bidang', 200)->nullable();
            $table->text('uraian');
            $table->decimal('anggaran', 18, 2)->default(0);
            $table->decimal('realisasi', 18, 2)->default(0);
            $table->string('status', 20)->default('draft')->comment('draft, direview, dipublikasikan');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->foreignId('published_by')->nullable()->constrained('users');
            $table->timestamp('tanggal_publikasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apbdes');
    }
};
