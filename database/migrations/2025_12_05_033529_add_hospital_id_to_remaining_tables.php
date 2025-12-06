<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // These tables track data that MUST be isolated per hospital
        $tables = [
            'doctors_new',          // Doctors
            'patients',             // Patients specific details
            'departments',          // Departments
            'drug_categories',      // Drug Categories
            'drug_mg',              // Drug Strengths
            'inventory_predictions',// AI Predictions
            'lab_tests',            // Lab Results
            'leave_requests',       // Staff Leave
            'medical_histories',    // Patient History
            'medications',          // Prescribed Meds
            'notifications',        // Alerts
            'ai_chat_histories',    // AI Context
            'chats',                // Doctor-Patient Chat
            'appointment_reasons',  // Settings
            'appointment_details',  // Clinical Notes
            'vitals',               // Vital Signs
            'clinical_notes',       // Doctor Notes
            'pharmacy_order_items', // Order Details
            'service_time_pricing', // Service Pricing
            'reminders',            // Reminders
            'prescription_items',   // Prescription Details
            'prescription_templates'// Templates
        ];

        // Get Default Hospital ID (Safeguard)
        $defaultId = DB::table('hospitals')->first()->id ?? 1;

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'hospital_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($defaultId) {
                    $table->unsignedBigInteger('hospital_id')->nullable()->after('id');
                    // We use cascade so if a hospital is deleted, their data is wiped
                    $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('cascade');
                });

                // Assign existing data to the default hospital
                DB::table($tableName)->update(['hospital_id' => $defaultId]);
            }
        }
    }

    public function down(): void
    {
        // No down method needed for this specific upgrade
    }
};