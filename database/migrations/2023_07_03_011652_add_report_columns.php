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
            $table->dropConstrainedForeignId('area_id');
            $table->foreignId('facility_id')->nullable()->constrained()->after('id');           
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
