<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik')->unique()->nullable()->after('email');
            $table->string('phone')->nullable()->after('nik');
            $table->text('address')->nullable()->after('phone');
            $table->string('rt', 3)->nullable()->after('address');
            $table->string('rw', 3)->nullable()->after('rt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nik', 'phone', 'address', 'rt', 'rw']);
        });
    }
};
