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
        Schema::create('consolidated_audit_reports', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('directory_id')->nullable()->constrained();
            $table->bigInteger('audit_report_id')->unsigned()->nullable(true);
            $table->foreign('audit_report_id')->references('id')->on('audit_reports')->onUpdate('cascade');
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
        Schema::dropIfExists('consolidated_audit_reports');
    }
};
