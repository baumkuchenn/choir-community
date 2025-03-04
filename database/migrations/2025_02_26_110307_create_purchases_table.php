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
            $table->string('gambar_pembayaran', 255)->nullable();
            $table->timestamp('waktu_pembayaran')->nullable();
            $table->enum('status', ['BAYAR', 'VERIFIKASI', 'SELESAI', 'BATAL']);
            $table->integer('total_tagihan');

            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users');
            $table->unsignedBigInteger('concerts_id');
            $table->foreign('concerts_id')->references('id')->on('concerts');

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
        Schema::dropIfExists('purchases');
    }
};
