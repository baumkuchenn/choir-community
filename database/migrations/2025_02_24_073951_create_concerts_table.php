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
            $table->string('gambar', 255)->nullable();
            $table->string('deskripsi', 1000)->nullable();
            $table->string('seating_plan', 255)->nullable();
            $table->string('syarat_ketentuan', 1000)->nullable();
            $table->enum('ebooklet', ['ya', 'tidak'])->nullable();
            $table->string('link_ebooklet', 255)->nullable();
            $table->enum('donasi', ['ya', 'tidak'])->nullable();
            $table->enum('tipe_kupon', ['kupon', 'referal', 'keduanya'])->nullable();
            $table->string('no_rekening', 45)->nullable();
            $table->string('pemilik_rekening', 150)->nullable();
            $table->string('berita_transfer', 255)->nullable();

            $table->unsignedBigInteger('banks_id')->nullable();
            $table->foreign('banks_id')->references('id')->on('banks');
            $table->unsignedBigInteger('events_id');
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
