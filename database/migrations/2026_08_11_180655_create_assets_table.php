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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_category_id')->constrained('asset_categories')->onDelete('restrict');
            $table->string('name');
            $table->string('location')->nullable();
            $table->enum('condition', ['baik', 'rusak_ringan', 'rusak_berat']);
            $table->year('acquisition_year')->nullable();
            $table->string('acquisition_source')->nullable();
            $table->decimal('value', 15, 2)->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['aktif', 'dipinjamkan', 'dihapus'])->default('aktif');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
