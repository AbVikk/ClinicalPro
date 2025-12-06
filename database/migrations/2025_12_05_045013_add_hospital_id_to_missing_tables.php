<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // These tables were missed in the previous updates
        $tables = [
            'doctor_schedules',
            'hospital_services',
            'attendances',
            'categories'
        ];

        // Get Default Hospital ID (Safeguard)
        $defaultId = DB::table('hospitals')->first()->id ?? 1;

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'hospital_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($defaultId) {
                    $table->unsignedBigInteger('hospital_id')->nullable()->after('id');
                    $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('cascade');
                });

                // Assign existing data to the default hospital
                DB::table($tableName)->update(['hospital_id' => $defaultId]);
            }
        }
    }

    public function down(): void
    {
        // No down needed for this fix
    }
};