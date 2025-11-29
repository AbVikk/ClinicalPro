<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Populate the new individual fields from the existing dosage_instructions field
        DB::table('prescription_items')->whereNotNull('dosage_instructions')->chunkById(100, function ($items) {
            foreach ($items as $item) {
                // Only update if the new fields are empty (meaning this is existing data)
                if (empty($item->medication_name) && empty($item->type) && empty($item->dosage) && 
                    empty($item->duration) && empty($item->use_pattern) && empty($item->instructions)) {
                    
                    // Parse the dosage_instructions field
                    $parts = explode('||', $item->dosage_instructions);
                    
                    // If we don't have 5 parts, try parsing with spaces (old format)
                    if (count($parts) < 5) {
                        $parts = explode(' ', $item->dosage_instructions, 5);
                        
                        // Handle the case where duration might contain spaces (e.g., "8 days")
                        $dosage = $parts[0] ?? '';
                        $type = $parts[1] ?? '';
                        $duration = $parts[2] ?? '';
                        $usePattern = $parts[3] ?? '';
                        $instructions = $parts[4] ?? '';
                        
                        // If usePattern looks like it might be part of duration (e.g., "days", "weeks")
                        // and duration is a number, combine them
                        if ($duration && $usePattern && is_numeric($duration) && 
                            in_array(strtolower($usePattern), ['days', 'day', 'weeks', 'week', 'months', 'month'])) {
                            $duration = $duration . ' ' . $usePattern;
                            $usePattern = $instructions;
                            $instructions = '';
                        }
                    } else {
                        // New format with || delimiter
                        $dosage = $parts[0] ?? '';
                        $type = $parts[1] ?? '';
                        $duration = $parts[2] ?? '';
                        $usePattern = $parts[3] ?? '';
                        $instructions = $parts[4] ?? '';
                    }
                    
                    // For existing data, we don't have the medication name in the dosage_instructions
                    // So we'll set it to a default value
                    $medicationName = 'Unknown Medication';
                    
                    // Update the record with individual fields
                    DB::table('prescription_items')
                        ->where('id', $item->id)
                        ->update([
                            'medication_name' => $medicationName,
                            'type' => $type,
                            'dosage' => $dosage,
                            'duration' => $duration,
                            'use_pattern' => $usePattern,
                            'instructions' => $instructions
                        ]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear the individual fields
        DB::table('prescription_items')->whereNotNull('medication_name')->update([
            'medication_name' => null,
            'type' => null,
            'dosage' => null,
            'duration' => null,
            'use_pattern' => null,
            'instructions' => null
        ]);
    }
};