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
        // This is the safest way to add new ENUM values in MySQL
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM(
            'pending',
            'confirmed',
            'in_progress',
            'completed',
            'cancelled',
            'missed',
            'checked_in',  /* <-- NEW */
            'vitals_taken' /* <-- NEW */
        ) NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This reverts it back to your original list
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM(
            'pending',
            'confirmed',
            'in_progress',
            'completed',
            'cancelled',
            'missed'
        ) NOT NULL DEFAULT 'pending'");
    }
};