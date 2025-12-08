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
        Schema::create('file_entries', function (Blueprint $table) {
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
            $table->id();
            $table->foreignId('file_id')->constrained('files')->cascadeOnDelete();
            $table->string('Year')->nullable()->index();
            $table->string('Industry_aggregation_NZSIOC')->nullable()->index();
            $table->string('Industry_code_NZSIOC')->nullable()->index();
            $table->string('Industry_name_NZSIOC')->nullable()->index();
            $table->string('Units')->nullable()->index();
            $table->string('Variable_code')->nullable()->index();
            $table->string('Variable_name')->nullable()->index();
            $table->string('Variable_category')->nullable()->index();
            $table->string('Value')->nullable()->index();
            $table->string('Industry_code_ANZSIC06')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_entries');
    }
};
