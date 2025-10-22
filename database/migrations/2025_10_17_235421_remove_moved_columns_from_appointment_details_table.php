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
        Schema::table('appointment_details', function (Blueprint $table) {
            // Remove columns that have been moved to separate tables
            $table->dropColumn([
                'clinical_notes',
                'skin_allergy',
                'temperature',
                'pulse',
                'respiratory_rate',
                'spo2',
                'height',
                'weight',
                'waist',
                'bsa',
                'bmi',
                'medications'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_details', function (Blueprint $table) {
            // Add back the columns that were removed
            $table->text('clinical_notes')->nullable();
            $table->text('skin_allergy')->nullable();
            $table->string('temperature')->nullable();
            $table->string('pulse')->nullable();
            $table->string('respiratory_rate')->nullable();
            $table->string('spo2')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('waist')->nullable();
            $table->string('bsa')->nullable();
            $table->string('bmi')->nullable();
            $table->text('medications')->nullable(); // JSON format
        });
    }
};