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
            $table->string('nama', 45);
            $table->enum('tipe', ['Internal', 'Eksternal', 'Konser']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('lokasi', 255);
            $table->enum('peran', ['Pengurus', 'Penyanyi', 'Keduanya']);
            $table->enum('panitia_eksternal', ['Ya', 'Tidak']);
            $table->enum('metode_rekrut_panitia', ['Pilih', 'Seleksi']);
            $table->enum('metode_rekrut_penyanyi', ['Pilih', 'Seleksi']);

            $table->unsignedBigInteger('sub_kegiatan_id')->nullable();
            $table->foreign('sub_kegiatan_id')->references('id')->on('events');

            $table->enum('isactive', [0, 1])->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
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
