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
        Schema::table('payments', function (Blueprint $table) {
            // Update the method enum to include all payment methods
            $table->enum('method', ['card_online', 'cash_in_clinic', 'bank_transfer', 'pos', 'paystack'])
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revert to original enum values
            $table->enum('method', ['card_online', 'cash_in_clinic', 'bank_transfer'])
                  ->change();
        });
    }
};