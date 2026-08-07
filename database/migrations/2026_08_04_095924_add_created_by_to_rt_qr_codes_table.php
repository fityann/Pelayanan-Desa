<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('rt_qr_codes', 'created_by')) {
            Schema::table('rt_qr_codes', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('scan_count')->constrained('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('rt_qr_codes', 'created_by')) {
            Schema::table('rt_qr_codes', function (Blueprint $table) {
                $table->dropConstrainedForeignId('created_by');
            });
        }
    }
};