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
            // Add consultation_id column
            $table->foreignId('consultation_id')
                  ->nullable()
                  ->constrained('consultations')
                  ->after('appointment_id');
            
            // Add clinic_id column
            $table->foreignId('clinic_id')
                  ->nullable()
                  ->constrained('clinics')
                  ->after('consultation_id');
            
            // Add method column
            $table->enum('method', ['card_online', 'cash_in_clinic', 'bank_transfer'])
                  ->after('amount');
            
            // Add reference column
            $table->string('reference', 255)
                  ->after('status');
            
            // Add transaction_date column
            $table->timestamp('transaction_date')
                  ->nullable()
                  ->after('reference');
            
            // Update status enum values
            $table->enum('status', ['paid', 'failed', 'refunded', 'pending_cash_verification'])
                  ->change();
                  
            // Remove transaction_id column (replaced by reference)
            $table->dropColumn('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['consultation_id']);
            $table->dropColumn('consultation_id');
            
            $table->dropForeign(['clinic_id']);
            $table->dropColumn('clinic_id');
            
            $table->dropColumn('method');
            $table->dropColumn('reference');
            $table->dropColumn('transaction_date');
            
            // Revert status enum values
            $table->enum('status', ['pending', 'completed', 'failed'])
                  ->change();
                  
            // Add back transaction_id column
            $table->string('transaction_id', 191)->unique()
                  ->after('amount');
        });
    }
};