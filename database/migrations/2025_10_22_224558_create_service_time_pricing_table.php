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
        Schema::create('service_time_pricing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->integer('duration_minutes'); // Duration in minutes (30, 40, 60, etc.)
            $table->decimal('price', 10, 2); // Price for this service-duration combination
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('service_id')->references('id')->on('hospital_services')->onDelete('cascade');
            
            // Ensure unique combination of service and duration
            $table->unique(['service_id', 'duration_minutes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_time_pricing');
    }
};