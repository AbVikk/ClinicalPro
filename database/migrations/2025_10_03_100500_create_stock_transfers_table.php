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
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('drug_batches')->onDelete('cascade');
            $table->foreignId('source_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('destination_id')->constrained('clinics')->onDelete('cascade');
            $table->integer('quantity');
            $table->enum('status', ['requested', 'shipped', 'received'])->default('requested');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};