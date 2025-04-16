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
        Schema::create('panitias', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('events_id');
            $table->foreign('events_id')->references('id')->on('events');
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users');
            $table->unsignedBigInteger('jabatan_id')->nullable();
            $table->foreign('jabatan_id')->references('id')->on('panitia_jabatans');

            $table->enum('tipe', ['internal', 'eksternal']);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panitias');
    }
};
