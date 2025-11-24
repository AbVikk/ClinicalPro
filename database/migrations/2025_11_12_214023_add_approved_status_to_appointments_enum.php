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
        // This takes our *last* list and just adds 'approved' to it.
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM(
            'pending',
            'confirmed',
            'in_progress',
            'completed',
            'cancelled',
            'missed',
            'checked_in',
            'vitals_taken',
            'approved'  /* <-- THIS IS THE FIX */
        ) NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This reverts it back to the list from the *previous* migration
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM(
            'pending',
            'confirmed',
            'in_progress',
            'completed',
            'cancelled',
            'missed',
            'checked_in',
            'vitals_taken'
        ) NOT NULL DEFAULT 'pending'");
    }
};