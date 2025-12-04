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
        Schema::create('file_contents', function (Blueprint $table) {
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
            $table->id();
            $table->foreignId('file_id')->constrained('files')->cascadeOnDelete()->index();
            $table->string('UNIQUE_KEY')->nullable()->unique()->index();
            $table->string('PRODUCT_TITLE')->nullable()->index();
            $table->text('PRODUCT_DESCRIPTION')->nullable();
            $table->string('STYLE#')->nullable()->index();
            $table->string('SANMAR_MAINFRAME_COLOR')->nullable()->index();
            $table->string('SIZE')->nullable();
            $table->string('COLOR_NAME')->nullable()->index();
            $table->float('PIECE_PRICE', 10, 2)->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Add index with prefix length
        Schema::table('file_contents', function (Blueprint $table) {
            $table->index([DB::raw('PRODUCT_DESCRIPTION(255)')], 'product_description_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_contents');
    }
};
