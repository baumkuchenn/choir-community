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
        Schema::create('collabs', function (Blueprint $table) {
            $table->unsignedBigInteger('choirs_id');
            $table->foreign('choirs_id')->references('id')->on('choirs');

            $table->unsignedBigInteger('events_id');
            $table->foreign('events_id')->references('id')->on('events');

            $table->enum('isactive', [0, 1])->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->primary(['choirs_id', 'events_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collabs');
    }
};
