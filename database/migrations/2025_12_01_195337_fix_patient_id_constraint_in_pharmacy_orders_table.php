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
        Schema::table('pharmacy_orders', function (Blueprint $table) {
            // Drop the existing foreign key constraint first
            $table->dropForeign(['patient_id']);
            // Drop the column
            $table->dropColumn('patient_id');
            // Recreate the column as nullable
            $table->foreignId('patient_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pharmacy_orders', function (Blueprint $table) {
            // Drop the nullable column
            $table->dropForeign(['patient_id']);
            $table->dropColumn('patient_id');
            // Recreate the original non-nullable column
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
        });
    }
};