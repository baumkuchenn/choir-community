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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->enum('parent_kegiatan', ['ya', 'tidak']);
            $table->enum('jenis_kegiatan', ['internal', 'eksternal', 'konser']);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('lokasi', 255)->nullable();
            $table->enum('peran', ['panitia', 'penyanyi', 'keduanya'])->nullable();
            $table->enum('panitia_eksternal', ['ya', 'tidak'])->nullable();
            $table->enum('visibility', ['public', 'inherited'])->nullable();

            $table->unsignedBigInteger('sub_kegiatan_id')->nullable();
            $table->foreign('sub_kegiatan_id')->references('id')->on('events');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
