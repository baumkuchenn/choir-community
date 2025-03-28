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
        Schema::create('pendaftar_nilais', function (Blueprint $table) {
            $table->unsignedBigInteger('pendaftars_id');
            $table->foreign('pendaftars_id')->references('id')->on('pendaftar_seleksis');
            $table->unsignedBigInteger('butirs_id');
            $table->foreign('butirs_id')->references('id')->on('butir_penilaians');
            $table->integer('nilai');

            $table->softDeletes();
            $table->timestamps();
            $table->primary(['pendaftars_id', 'butirs_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftar_nilais');
    }
};
