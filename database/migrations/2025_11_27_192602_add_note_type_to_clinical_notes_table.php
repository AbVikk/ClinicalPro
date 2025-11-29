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
        Schema::table('clinical_notes', function (Blueprint $table) {
            if (!Schema::hasColumn('clinical_notes', 'note_type')) {
                $table->string('note_type')->nullable()->after('skin_allergy');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinical_notes', function (Blueprint $table) {
            if (Schema::hasColumn('clinical_notes', 'note_type')) {
                $table->dropColumn('note_type');
            }
        });
    }
};