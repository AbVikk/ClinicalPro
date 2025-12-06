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
        // 1. Create the Hospitals Table
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain_prefix', 50)->unique()->nullable(); // subdomain
            $table->string('address')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
            $table->string('subscription_plan')->default('basic');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Create a Default Hospital (Trinexafrica Demo) to prevent crashes
        $defaultHospitalId = DB::table('hospitals')->insertGetId([
            'name' => 'Trinexafrica Demo Hospital',
            'domain_prefix' => 'demo',
            'subscription_plan' => 'enterprise',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Add hospital_id to ALL critical tables
        $tables = [
            'users',
            'clinics',
            'drugs',
            'drug_batches',
            'clinic_inventories', // Inventory belongs to a clinic, which belongs to a hospital, but direct link helps queries
            'appointments',
            'consultations',
            'prescriptions',
            'payments',
            'pharmacy_orders',
            'stock_transfers',
            'invitations'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($defaultHospitalId) {
                    // Add the column
                    $table->unsignedBigInteger('hospital_id')->nullable()->after('id');
                    
                    // Add Foreign Key constraint
                    $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('cascade');
                });

                // Assign existing data to the default hospital
                DB::table($tableName)->update(['hospital_id' => $defaultHospitalId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We cannot easily reverse this without losing data structure, 
        // but we can drop the columns.
        $tables = [
            'users', 'clinics', 'drugs', 'drug_batches', 'clinic_inventories', 
            'appointments', 'consultations', 'prescriptions', 'payments', 
            'pharmacy_orders', 'stock_transfers', 'invitations'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['hospital_id']);
                    $table->dropColumn('hospital_id');
                });
            }
        }

        Schema::dropIfExists('hospitals');
    }
};