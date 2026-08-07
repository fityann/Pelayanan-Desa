<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            if (Schema::hasIndex('pengajuan_surats', 'pengajuan_surats_user_id_foreign')) {
                $table->dropIndex('pengajuan_surats_user_id_foreign');
            }
        });

        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();

            $table->string('nama_pemohon', 100)->nullable()->after('user_id');
            $table->string('nik_pemohon', 20)->nullable()->after('nama_pemohon');
            $table->string('alamat_pemohon')->nullable()->after('nik_pemohon');
            $table->string('no_whatsapp', 20)->nullable()->after('alamat_pemohon');
            $table->string('kode_tracking', 30)->nullable()->unique()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {
            $table->dropUnique('pengajuan_surats_kode_tracking_unique');
            $table->dropColumn(['kode_tracking', 'no_whatsapp', 'alamat_pemohon', 'nik_pemohon', 'nama_pemohon']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
