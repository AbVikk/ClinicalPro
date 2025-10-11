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
        Schema::table('users', function (Blueprint $table) {
            // Modify the role column to include new pharmacist roles
            $table->enum('role', [
                'admin', 
                'doctor', 
                'patient', 
                'nurse', 
                'receptionist', 
                'billing_staff',
                'primary_pharmacist',
                'senior_pharmacist',
                'clinic_pharmacist'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert to previous role enum values
            $table->enum('role', [
                'admin', 
                'doctor', 
                'patient', 
                'nurse', 
                'receptionist', 
                'billing_staff'
            ])->change();
        });
    }
};