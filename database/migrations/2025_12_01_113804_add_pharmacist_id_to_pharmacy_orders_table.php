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
            $table->foreignId('pharmacist_id')->constrained('users')->onDelete('cascade')->after('clinic_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pharmacy_orders', function (Blueprint $table) {
            $table->dropForeign(['pharmacist_id']);
            $table->dropColumn('pharmacist_id');
        });
    }
};