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
        Schema::create('appointment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id');
            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            
            // Patient Information
            $table->string('blood_group')->nullable();
            
            // Laboratory Tests
            $table->text('lab_tests')->nullable(); // JSON format
            
            // Complaints
            $table->text('complaints')->nullable(); // JSON format
            
            // Diagnosis
            $table->text('diagnosis')->nullable(); // JSON format
            
            // Advice
            $table->text('advice')->nullable();
            
            // Follow Up
            $table->date('follow_up_date')->nullable();
            $table->time('follow_up_time')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_details');
    }
};