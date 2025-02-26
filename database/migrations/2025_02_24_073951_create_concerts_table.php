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
        Schema::create('concerts', function (Blueprint $table) {
            $table->id();
            $table->string('gambar', 255);
            $table->string('deskripsi', 1000);
            $table->string('seating_plan', 255);
            $table->string('syarat_ketentuan', 1000);
            $table->string('link_ebooklet', 255);
            $table->enum('donasi', ['Ya', 'Tidak']);

            $table->unsignedBigInteger('events_id')->nullable();
            $table->foreign('events_id')->references('id')->on('events');

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
        Schema::dropIfExists('concerts');
    }
};
