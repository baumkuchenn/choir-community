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
        Schema::create('post_concerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('posts_id');
            $table->foreign('posts_id')->references('id')->on('posts');
            $table->unsignedBigInteger('concerts_id');
            $table->foreign('concerts_id')->references('id')->on('concerts');
            $table->unsignedBigInteger('choirs_id');
            $table->foreign('choirs_id')->references('id')->on('choirs');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_concerts');
    }
};
