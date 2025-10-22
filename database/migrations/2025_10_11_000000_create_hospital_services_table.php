<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the 'hospital_services' table to securely store service details and pricing.
     */
    public function up(): void
    {
        Schema::create('hospital_services', function (Blueprint $table) {
            $table->id();
            
            // Core Service Details
            $table->string('service_name', 191)->unique();
            $table->string('service_type')->comment('e.g., Consultation, Treatment, Diagnostic');
            $table->string('description')->nullable();

            // Pricing Information (CRITICAL: Use decimal for currency)
            // Storing price as DECIMAL(10, 2) ensures accuracy and avoids floating-point errors.
            $table->decimal('price_amount', 10, 2); 
            $table->string('price_currency', 3)->default('NGN')->comment('Currency code, e.g., NGN, USD');
            
            // Operational Status
            $table->boolean('is_active')->default(true)->comment('Service can be temporarily disabled.');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_services');
    }
};