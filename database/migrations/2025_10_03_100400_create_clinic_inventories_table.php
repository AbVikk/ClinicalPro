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
        Schema::create('clinic_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('drug_batches')->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->integer('stock_level');
            $table->integer('reorder_point')->default(10);
            $table->timestamps();
            
            // Ensure unique combination of batch and clinic
            $table->unique(['batch_id', 'clinic_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_inventories');
    }
};