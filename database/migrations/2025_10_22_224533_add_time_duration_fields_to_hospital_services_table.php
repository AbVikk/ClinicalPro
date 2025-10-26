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
        Schema::table('hospital_services', function (Blueprint $table) {
            // Add fields for time-based pricing
            $table->json('time_pricing')->nullable()->after('price_amount');
            // Add a default duration in minutes
            $table->integer('default_duration')->default(30)->after('price_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospital_services', function (Blueprint $table) {
            $table->dropColumn(['time_pricing', 'default_duration']);
        });
    }
};