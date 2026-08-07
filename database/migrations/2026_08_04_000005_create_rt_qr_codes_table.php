<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rt_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->string('nama_rt', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('qr_code_path', 500)->nullable();
            $table->string('qr_code_url', 500)->nullable();
            $table->string('status', 20)->default('aktif')->comment('aktif, nonaktif');
            $table->timestamp('tanggal_generate')->useCurrent();
            $table->integer('scan_count')->default(0);
            $table->timestamps();
            
            $table->unique(['rt', 'rw']);
        });

        Schema::create('qr_code_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_qr_code_id')->constrained('rt_qr_codes')->cascadeOnDelete();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('device_type')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->string('keterangan', 200)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_code_logs');
        Schema::dropIfExists('rt_qr_codes');
    }
};