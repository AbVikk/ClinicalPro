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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users');
            $table->foreignId('doctor_id')->constrained('users');
            $table->foreignId('location_id')->constrained('clinics');
            $table->enum('delivery_channel', ['in_clinic', 'virtual']);
            $table->string('service_type', 100);
            $table->decimal('fee', 10, 2);
            $table->enum('status', ['scheduled', 'started', 'completed', 'missed', 'cancelled']);
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};