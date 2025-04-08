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
        Schema::create('perulangans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_mulai');
            $table->enum('frekuensi', ['minggu', 'bulan']);
            $table->integer('interval');
            $table->json('hari')->nullable();
            $table->enum('tipe_selesai', ['tidak', 'tanggal', 'jumlah']);
            $table->date('tanggal_selesai')->nullable();
            $table->integer('jumlah')->nullable();

            $table->unsignedBigInteger('events_id');
            $table->foreign('events_id')->references('id')->on('events');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perulangans');
    }
};
