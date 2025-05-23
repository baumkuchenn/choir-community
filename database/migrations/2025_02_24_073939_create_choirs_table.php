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
        Schema::create('choirs', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 45);
            $table->string('nama_singkat', 25);
            $table->string('logo', 255)->nullable();
            $table->string('profil', 255)->nullable();
            $table->enum('tipe', ['SSAATTBB', 'SSAA', 'TTBB']);
            $table->text('alamat');
            $table->text('deskripsi');
            $table->enum('jenis_rekrutmen', ['invite', 'seleksi'])->default('seleksi');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choirs');
    }
};
