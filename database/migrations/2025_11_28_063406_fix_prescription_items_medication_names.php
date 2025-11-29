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
        // Fix medication names by looking them up from the medications table
        // We'll join prescription_items with appointments and medications tables
        DB::table('prescription_items as pi')
            ->join('prescriptions as p', 'pi.prescription_id', '=', 'p.id')
            ->join('appointments as a', 'p.consultation_id', '=', 'a.consultation_id')
            ->join('medications as m', 'a.id', '=', 'm.appointment_id')
            ->whereNull('pi.medication_name')
            ->orWhere('pi.medication_name', '=', 'Unknown Medication')
            ->update([
                'pi.medication_name' => DB::raw('m.medication_name'),
                'pi.type' => DB::raw('COALESCE(pi.type, m.type)'),
                'pi.dosage' => DB::raw('COALESCE(pi.dosage, m.dosage)'),
                'pi.duration' => DB::raw('COALESCE(pi.duration, m.duration)'),
                'pi.use_pattern' => DB::raw('COALESCE(pi.use_pattern, m.use_pattern)'),
                'pi.instructions' => DB::raw('COALESCE(pi.instructions, m.instructions)')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse operation needed
    }
};