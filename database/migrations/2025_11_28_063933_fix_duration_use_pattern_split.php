<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix any records where duration and use_pattern are incorrectly split
        DB::table('prescription_items')->whereNotNull('duration')->chunkById(100, function ($items) {
            foreach ($items as $item) {
                // Check if duration is numeric and use_pattern contains time units
                if (is_numeric($item->duration) && 
                    in_array(strtolower($item->use_pattern ?? ''), ['days', 'day', 'weeks', 'week', 'months', 'month'])) {
                    
                    // Combine duration and use_pattern correctly
                    $correctDuration = $item->duration . ' ' . $item->use_pattern;
                    
                    // Get the next field's value to shift it properly
                    $correctUsePattern = $item->instructions ?? '';
                    $correctInstructions = ''; // Instructions would be empty or need to be determined from context
                    
                    // Update the record
                    DB::table('prescription_items')
                        ->where('id', $item->id)
                        ->update([
                            'duration' => $correctDuration,
                            'use_pattern' => $correctUsePattern,
                            'instructions' => $correctInstructions
                        ]);
                }
                
                // Also fix cases where we might have "8 days" in use_pattern and instructions in use_pattern
                if (preg_match('/^\d+\s+(days|day|weeks|week|months|month)$/i', $item->duration ?? '') && 
                    empty($item->use_pattern)) {
                    // Duration is already correct, no action needed
                } else if (is_numeric($item->duration) && 
                          preg_match('/^(days|day|weeks|week|months|month)/i', $item->use_pattern ?? '')) {
                    // Need to combine them
                    $correctDuration = $item->duration . ' ' . $item->use_pattern;
                    DB::table('prescription_items')
                        ->where('id', $item->id)
                        ->update([
                            'duration' => $correctDuration,
                            'use_pattern' => $item->instructions ?? '',
                            'instructions' => ''
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
        // No reverse operation needed
    }
};