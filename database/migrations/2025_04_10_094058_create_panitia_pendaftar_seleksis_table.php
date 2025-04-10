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
        Schema::create('panitia_pendaftar_seleksis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users');
            $table->unsignedBigInteger('seleksis_id');
            $table->foreign('seleksis_id')->references('id')->on('seleksis');

            $table->enum('kehadiran', ['belum', 'ya', 'tidak'])->default('belum');
            $table->enum('tipe', ['internal', 'eksternal']);
            $table->text('hasil_wawancara')->nullable();
            $table->enum('lolos', ['belum', 'ya', 'tidak'])->default('belum');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panitia_pendaftar_seleksis');
    }
};
