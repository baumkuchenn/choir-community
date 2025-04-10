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
        Schema::create('panitia_jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('akses_event', [0, 1])->default(0);
            $table->enum('akses_eticket', [0, 1])->default(0);

            $table->unsignedBigInteger('divisi_id');
            $table->foreign('divisi_id')->references('id')->on('panitia_divisis');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panitai_jabatans');
    }
};
