<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations (Add the new column).
     */
    public function up(): void
    {
        // We use Schema::table() because the table already exists.
        // This ONLY adds the new column.
        Schema::table('medications', function (Blueprint $table) {
            $table->string('use_pattern')->nullable()->after('duration');
        });
    }

    /**
     * Reverse the migrations (Remove the new column).
     */
    public function down(): void
    {
        // We use Schema::table() to reverse only the change we made.
        Schema::table('medications', function (Blueprint $table) {
            $table->dropColumn('use_pattern');
        });
    }
};