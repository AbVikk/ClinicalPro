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
        // We use raw SQL because Doctrine DBAL struggles with changing ENUMs sometimes.
        // This adds 'pending' and 'completed' to the allowed list.
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('paid', 'failed', 'refunded', 'pending_cash_verification', 'pending', 'completed') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original restrictive list
        // Note: This might fail if you have 'pending' data in the DB, so be careful rolling back.
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('paid', 'failed', 'refunded', 'pending_cash_verification') NOT NULL");
    }
};