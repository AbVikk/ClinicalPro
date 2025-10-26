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
            // Get the list of columns in the table
            $columns = Schema::getColumnListing('appointment_details');
            
            // Columns to remove if they exist
            $columnsToRemove = [
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
            ];
            
            // Filter to only include columns that actually exist
            $existingColumns = array_intersect($columnsToRemove, $columns);
            
            // Remove columns that exist
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointment_details', function (Blueprint $table) {
            // Check if columns exist before adding them back
            $columns = Schema::getColumnListing('appointment_details');
            
            if (!in_array('clinical_notes', $columns)) {
                $table->text('clinical_notes')->nullable();
            }
            if (!in_array('skin_allergy', $columns)) {
                $table->text('skin_allergy')->nullable();
            }
            if (!in_array('temperature', $columns)) {
                $table->string('temperature')->nullable();
            }
            if (!in_array('pulse', $columns)) {
                $table->string('pulse')->nullable();
            }
            if (!in_array('respiratory_rate', $columns)) {
                $table->string('respiratory_rate')->nullable();
            }
            if (!in_array('spo2', $columns)) {
                $table->string('spo2')->nullable();
            }
            if (!in_array('height', $columns)) {
                $table->string('height')->nullable();
            }
            if (!in_array('weight', $columns)) {
                $table->string('weight')->nullable();
            }
            if (!in_array('waist', $columns)) {
                $table->string('waist')->nullable();
            }
            if (!in_array('bsa', $columns)) {
                $table->string('bsa')->nullable();
            }
            if (!in_array('bmi', $columns)) {
                $table->string('bmi')->nullable();
            }
            if (!in_array('medications', $columns)) {
                $table->text('medications')->nullable(); // JSON format
            }
        });
    }
};