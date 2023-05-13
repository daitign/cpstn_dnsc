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
        Schema::create('template_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained();
            $table->string('name')->nullable();
            $table->foreignId('area_id')->nullable()->constrained();
            $table->foreignId('role_id')->nullable()->constrained();
            $table->foreignId('directory_id')->nullable()->constrained();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->date('date')->nullable();
            $table->foreignId('file_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_histories');
    }
};
