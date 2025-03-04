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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->unsignedBigInteger('purchases_id');
            $table->foreign('purchases_id')->references('id')->on('purchases');
            $table->unsignedBigInteger('ticket_types_id');
            $table->foreign('ticket_types_id')->references('id')->on('ticket_types');
            $table->primary(['purchases_id', 'ticket_types_id']);

            $table->integer('jumlah');
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
        Schema::dropIfExists('purchase_details');
    }
};
