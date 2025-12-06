<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get Default Hospital ID (Safeguard)
        $defaultId = DB::table('hospitals')->first()->id ?? 1;

        if (Schema::hasTable('audit_logs') && !Schema::hasColumn('audit_logs', 'hospital_id')) {
            Schema::table('audit_logs', function (Blueprint $table) use ($defaultId) {
                $table->unsignedBigInteger('hospital_id')->nullable()->after('id');
                // Cascade delete: If hospital is deleted, wipe their security logs
                $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('cascade');
            });

            // Assign existing logs to the default hospital
            DB::table('audit_logs')->update(['hospital_id' => $defaultId]);
        }
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropForeign(['hospital_id']);
            $table->dropColumn('hospital_id');
        });
    }
};