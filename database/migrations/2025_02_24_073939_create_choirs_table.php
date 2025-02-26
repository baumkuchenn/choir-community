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
            $table->string('logo', 255);
            $table->string('profil', 255);
            $table->enum('tipe', ['SSAATTBB', 'SSAA', 'TTBB']);
            $table->string('kota', 45);
            $table->string('alamat', 255);
            $table->string('deskripsi', 1000);

            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users');

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
        Schema::dropIfExists('choirs');
    }
};
