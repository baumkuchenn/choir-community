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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->string('barcode_code', 45);
            $table->string('barcode_image', 255);
            $table->enum('check_in', ['ya', 'tidak'])->default('tidak');
            $table->timestamp('waktu_check_in')->nullable();

            $table->unsignedBigInteger('invoices_id')->nullable();
            $table->foreign('invoices_id')->references('id')->on('invoices');
            $table->unsignedBigInteger('invitations_id')->nullable();
            $table->foreign('invitations_id')->references('id')->on('ticket_invitations');
            $table->unsignedBigInteger('ticket_types_id');
            $table->foreign('ticket_types_id')->references('id')->on('ticket_types');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
