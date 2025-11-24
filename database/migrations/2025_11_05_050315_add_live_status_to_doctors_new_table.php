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
        Schema::table('doctors_new', function (Blueprint $table) {
            //
            $table->string('live_status') // Creates a 'string' column
                  ->default('Available')   // Sets the default value to 'Available'
                  ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors_new', function (Blueprint $table) {
            //
            $table->dropColumn('live_status');
        });
    }
};
