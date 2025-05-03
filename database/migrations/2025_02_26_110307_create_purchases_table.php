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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->timestamp('waktu_pembelian')->useCurrent();
            $table->text('gambar_pembayaran')->nullable();
            $table->timestamp('waktu_pembayaran')->nullable();
            $table->enum('status', ['bayar', 'verifikasi', 'selesai', 'batal']);
            $table->integer('total_tagihan');

            $table->unsignedBigInteger('kupons_id')->nullable();
            $table->foreign('kupons_id')->references('id')->on('kupons');
            $table->unsignedBigInteger('referals_id')->nullable();
            $table->foreign('referals_id')->references('id')->on('kupons');
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users');
            $table->unsignedBigInteger('concerts_id');
            $table->foreign('concerts_id')->references('id')->on('concerts');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
