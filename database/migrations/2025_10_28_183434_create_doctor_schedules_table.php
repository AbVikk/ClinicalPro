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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->string('location')->nullable();
            $table->date('start_date')->nullable(); // For the range
            $table->date('end_date')->nullable();   // For the range
            $table->time('start_time');
            $table->time('end_time');
            $table->string('recurrence')->nullable(); // 'weekly' or 'monthly'
            $table->string('day_of_week'); // 'monday', 'tuesday', etc.
            $table->string('session_type')->nullable(); // 'morning' or 'noon'
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
