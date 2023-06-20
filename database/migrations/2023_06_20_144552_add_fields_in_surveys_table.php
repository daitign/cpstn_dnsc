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
        Schema::table('surveys', function (Blueprint $table) {    
            $table->string('email')->after('name');        
            $table->foreignId('facility_id')->nullable()->constrained()->after('id');
            $table->integer('cordiality')->nullable()->after('suggestions');
            $table->integer('engagement')->nullable()->after('suggestions');
            $table->integer('promptness')->nullable()->after('suggestions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
