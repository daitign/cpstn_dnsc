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
        Schema::table('consolidated_audit_reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('audit_report_id');
            $table->foreignId('audit_plan_id')->nullable()->after('user_id')->constrained();
        });

        Schema::table('audit_reports', function (Blueprint $table) {
            $table->foreignId('audit_plan_id')->nullable()->after('user_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
