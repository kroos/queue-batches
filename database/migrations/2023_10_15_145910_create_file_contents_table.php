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
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->integer('file_id')->nullable();
            $table->integer('UNIQUE_KEY')->unique();
            $table->string('PRODUCT_TITLE')->nullable();
            $table->text('PRODUCT_DESCRIPTION')->nullable();
            $table->string('STYLE#')->nullable();
            $table->text('SANMAR_MAINFRAME_COLOR')->nullable();
            $table->string('SIZE')->nullable();
            $table->string('COLOR_NAME')->nullable();
            $table->float('PIECE_PRICE', 8, 2)->nullable();
            $table->timestamps();
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
