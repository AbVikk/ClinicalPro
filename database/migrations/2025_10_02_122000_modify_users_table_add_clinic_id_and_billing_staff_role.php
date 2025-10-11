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
        Schema::table('users', function (Blueprint $table) {
            // Add the clinic_id column as a foreign key to clinics table
            $table->foreignId('clinic_id')
                  ->nullable() // Super Admin may be null
                  ->constrained('clinics')
                  ->after('role');
        });

        // Update the role enum to include 'billing_staff'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('patient', 'doctor', 'assistant', 'admin', 'donor', 'billing_staff')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['clinic_id']);
            $table->dropColumn('clinic_id');
        });

        // Revert the role enum to original values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('patient', 'doctor', 'assistant', 'admin', 'donor')");
    }
};