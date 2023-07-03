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
        Schema::create('file_remarks', function (Blueprint $table) {
            $table->id();
            
            $table->bigInteger('file_id')->unsigned()->nullable(true);
            $table->foreign('file_id')->references('id')->on('files')->onUpdate('cascade');
            
            $table->string('type', 200)->nullable(false);
            $table->text('comments')->nullable();
            
            $table->bigInteger('user_id')->unsigned()->nullable(true);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');

            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_remarks');
    }
};
