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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_id')->nullable()->constrained();
            $table->foreignId('program_id')->nullable()->constrained();
            $table->foreignId('role_id')->nullable()->constrained();
            $table->foreignId('directory_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('folder_name')->nullable();
            $table->foreignId('template_id')->nullable()->constrained();
            $table->softDeletesTz();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
