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
        // The role column already includes 'nurse', so we don't need to modify it
        // Just ensure any existing 'clinic_staff' roles are updated to 'nurse'
        DB::table('users')->where('role', 'clinic_staff')->update(['role' => 'nurse']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert as we're not changing the schema
    }
};