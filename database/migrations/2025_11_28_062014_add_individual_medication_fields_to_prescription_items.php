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
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->string('medication_name')->nullable()->after('drug_id');
            $table->string('type')->nullable()->after('medication_name');
            $table->string('dosage')->nullable()->after('type');
            $table->string('duration')->nullable()->after('dosage');
            $table->string('use_pattern')->nullable()->after('duration');
            $table->text('instructions')->nullable()->after('use_pattern');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            $table->dropColumn(['medication_name', 'type', 'dosage', 'duration', 'use_pattern', 'instructions']);
        });
    }
};