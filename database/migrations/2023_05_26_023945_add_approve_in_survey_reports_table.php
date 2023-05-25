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
        Schema::table('survey_reports', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('file_id')->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable(true);
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade');
        });

        Schema::table('consolidated_audit_reports', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('file_id')->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable(true);
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
