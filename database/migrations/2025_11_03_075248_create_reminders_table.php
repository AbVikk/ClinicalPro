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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            
            // The user who CREATED the reminder (e.g., the Nurse or Admin).
            $table->unsignedBigInteger('creator_id'); 
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            
            // The user who should RECEIVE the notification (usually the creator, but could be a doctor).
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // The specific time and date the reminder should be sent. This is crucial for the Scheduler.
            $table->dateTime('scheduled_at'); 

            // The message content (e.g., "Call Patient X about follow-up").
            $table->text('message'); 
            
            // Optional: Link to a consultation/appointment (if the reminder is about a specific event).
            $table->unsignedBigInteger('consultation_id')->nullable(); 
            // Assuming you have a 'consultations' table
            $table->foreign('consultation_id')->references('id')->on('consultations')->onDelete('cascade'); 

            // Status helps track if it has been sent or dismissed.
            $table->enum('status', ['pending', 'sent', 'dismissed'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};