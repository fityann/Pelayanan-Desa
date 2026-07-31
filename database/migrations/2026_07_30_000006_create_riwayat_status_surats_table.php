<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_status_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')->constrained('pengajuan_surats')->cascadeOnDelete();
            $table->string('status', 50);
            $table->text('catatan')->nullable();
            $table->foreignId('oleh_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['pengajuan_surat_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_status_surats');
    }
};
