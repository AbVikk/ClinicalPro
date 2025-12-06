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
        Schema::create('inventory_predictions', function (Blueprint $table) {
            $table->id();
            $table->string('drug_name', 100);
            $table->string('month', 20);
            $table->integer('year');
            $table->integer('predicted_quantity');
            $table->integer('reorder_point');
            $table->integer('recommended_order_quantity');
            $table->json('risk_factors')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index(['drug_name', 'year']);
            $table->index('generated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_predictions');
    }
};