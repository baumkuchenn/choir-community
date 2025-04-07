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
        Schema::create('penyanyis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('events_id');
            $table->foreign('events_id')->references('id')->on('events');
            $table->unsignedBigInteger('members_id');
            $table->foreign('members_id')->references('id')->on('members');

            $table->string('suara', 45)->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyanyis');
    }
};
