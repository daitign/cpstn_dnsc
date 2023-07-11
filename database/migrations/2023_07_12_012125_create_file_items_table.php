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
        Schema::create('file_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->nullable()->constrained();
            $table->foreignId('file_history_id')->nullable()->constrained();
            $table->string('file_name', 200)->nullable(false);
            $table->string('file_mime', 100)->nullable(false);
            $table->text('container_path')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_items');
    }
};
