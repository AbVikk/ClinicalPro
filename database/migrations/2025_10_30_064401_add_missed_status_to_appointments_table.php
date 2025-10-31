<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- Don't forget this line!

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This SQL command tells the database to add 'missed' to the list of allowed words
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'missed')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This changes it back if you ever need to undo (rollback) the migration
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled')");
    }
};