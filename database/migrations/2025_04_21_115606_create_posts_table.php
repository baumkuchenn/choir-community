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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('forums_id')->nullable();
            $table->foreign('forums_id')->references('id')->on('forums');
            $table->unsignedBigInteger('topics_id')->nullable();
            $table->foreign('topics_id')->references('id')->on('forum_topics');
            $table->unsignedBigInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users');

            $table->text('isi');
            $table->enum('tipe', ['post', 'thread']);

            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')->references('id')->on('posts');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
