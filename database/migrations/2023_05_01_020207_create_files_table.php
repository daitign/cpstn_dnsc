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
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('directory_id')->unsigned()->nullable(true);
            $table->foreign('directory_id')->references('id')->on('directories')->onUpdate('cascade');
            
            $table->bigInteger('user_id')->unsigned()->nullable(true);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');

            $table->string('file_name', 200)->nullable(false);
            $table->string('file_mime', 100)->nullable(false);
            $table->string('container_path', 250)->nullable(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
