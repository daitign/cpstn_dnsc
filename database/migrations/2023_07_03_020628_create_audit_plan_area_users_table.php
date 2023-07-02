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
        Schema::dropIfExists('audit_plan_users');
        Schema::create('audit_plan_area_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_plan_id')->nullable()->constrained();
            $table->foreignId('audit_plan_area_id')->nullable()->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_plan_area_users');
    }
};
