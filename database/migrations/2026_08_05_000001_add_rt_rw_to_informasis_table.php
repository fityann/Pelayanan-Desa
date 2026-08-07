<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('informasis', function (Blueprint $table) {
            // Relasi berita/pengumuman/agenda ke wilayah RT/RW.
            // NULL berarti berlaku untuk seluruh desa.
            $table->string('rt', 3)->nullable()->after('lokasi');
            $table->string('rw', 3)->nullable()->after('rt');
        });
    }

    public function down(): void
    {
        Schema::table('informasis', function (Blueprint $table) {
            $table->dropColumn(['rt', 'rw']);
        });
    }
};
