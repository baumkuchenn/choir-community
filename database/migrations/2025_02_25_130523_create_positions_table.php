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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('akses_event', [0, 1])->default(0);
            $table->enum('akses_member', [0, 1])->default(0);
            $table->enum('akses_roles', [0, 1])->default(0);
            $table->enum('akses_eticket', [0, 1])->default(0);
            $table->enum('akses_forum', [0, 1])->default(0);

            $table->unsignedBigInteger('divisions_id');
            $table->foreign('divisions_id')->references('id')->on('divisions');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
