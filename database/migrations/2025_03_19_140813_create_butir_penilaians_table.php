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
        Schema::create('butir_penilaians', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('bobot_nilai');
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
        Schema::dropIfExists('butir_penilaians');
    }
};
