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
        Schema::create('panitia_divisis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nama_singkat', 45);

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
        Schema::dropIfExists('panitia_divisis');
    }
};
