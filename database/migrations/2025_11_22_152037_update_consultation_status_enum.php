<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // We add 'pending' to the list so we can mark appointments as "booked but not paid"
        // This ensures they block the time slot so no one else can take it.
        DB::statement("ALTER TABLE consultations MODIFY COLUMN status ENUM('scheduled', 'started', 'completed', 'missed', 'cancelled', 'pending') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE consultations MODIFY COLUMN status ENUM('scheduled', 'started', 'completed', 'missed', 'cancelled') NOT NULL DEFAULT 'scheduled'");
    }
};